<?php
namespace App\Controller;

use App\Entity\Noter;
use App\Entity\Projets;
use App\Form\ProjetAddType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class ProjetController extends AbstractController
{
    public function projets(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(Projets::class);
        $projets = $repository->findAll();

        $repository2 = $em->getRepository(Noter::class);
        $notes = $repository2->findAll();

        foreach ($projets as $projet) {
            $tag = $projet->getTag();
            $tag = unserialize($tag);
            $tag = implode(" ", $tag);
        }

        return $this->render(
            'projet/accueil_proj.html.twig',
            array(
                'projets' => $projets,
                'notes' => $notes,
                'tag' => $tag,
            )
        );
    }

    //Méthode permettant d'utiliser la fonction explode avec plusieurs séparateurs
    private function multiexplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string); //On remplace tous les séparateurs par le premier séparateur du tableau $delimiters
        $launch = explode($delimiters[0], $ready); //On appelle la fonction explode avec le premier séparateur
        return  $launch;
    }

    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $projet = new Projets();

        $em = $doctrine->getManager();
        // $repository = $em->getRepository(User::class);
        // $users = $repository->findBy(
        //     array('id' => $id0)
        // );

        $user = $this->getUser()->getId();

        $user = $em->getRepository(User::class)->find($user);

        $form = $this->createForm(ProjetAddType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            $projet->setIdUser($user);

            $tag = $form->get('tag')->getData();
            $jsonTag = $this->multiexplode(array(",",".","|",":"),$tag);
            $jsonTag = serialize($jsonTag);
            $projet->setTag($jsonTag);

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $projet->setImage("images/" . $newFilename);
            }

            $em->persist($projet);
            $em->flush(); //C'est là qu'est insérée l'image

            return $this->redirectToRoute('profil');
        }

        return $this->render('projet/add_proj.html.twig', [
            'projForm' => $form->createView(),
        ]);
    }
}