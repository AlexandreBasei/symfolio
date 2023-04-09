<?php
namespace App\Controller;

use App\Entity\Noter;
use Doctrine\DBAL\Connection;   
use App\Entity\Projets;
use App\Form\ProjetAddType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\ProjetEditType;
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
use Symfony\Component\Routing\Annotation\Route;

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

    public function delete_projet($idProjet, $idUser, $idPage, ManagerRegistry $doctrine, Security $security): Response{

        $role = [];

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
            $id0 = $this->getUser()->getId();
            $role = $this->getUser()->getRoles();

            if ($id0 == $idUser || in_array("ROLE_ADMIN", $role)) //On vérifie que c'est bien l'utilisateur connecté qui veut supprimer un projet
            {
                //Récupération du projet
                $em = $doctrine->getManager();
                $repository = $em->getRepository(Projets::class);
                $projet = $repository->find($idProjet);
            
                // Suppression du projet
                $em->remove($projet);
                $em->flush();
            }
        }

        return $this->redirectToRoute('profil', ['id' => $idPage]);

    }

    public function edit_projet($idProjet, $idUser, $idPage, ManagerRegistry $doctrine, Security $security, Request $request, SluggerInterface $slugger): Response{

        $role = [];

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
            $id0 = $this->getUser()->getId();
            $role = $this->getUser()->getRoles();

            if ($id0 == $idUser || in_array("ROLE_ADMIN", $role)) //On vérifie que c'est bien l'utilisateur connecté qui veut supprimer un projet
            {
                //Récupération du projet
                $em = $doctrine->getManager();
                $repository = $em->getRepository(Projets::class);
                $projet = $repository->find($idProjet);
                
                if (!$projet) {
                    throw $this->createNotFoundException("Le projet avec l'ID $idProjet n'existe pas");
                }

                $form = $this->createForm(ProjetEditType::class, $projet, ['compound' => true]);
                $form->handleRequest($request);
                
                
                // Modification du projet
                if ($form->isSubmitted() && $form->isValid()) {
                    $image = $form->get('image')->getData();
        
                    $tag = $form->get('tag')->getData();
                    $jsonTag = $this->multiexplode(array(",",".","|",":"),$tag);
                    $jsonTag = serialize($jsonTag);
        
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
                    $em->flush();

                    return $this->redirectToRoute('profil', ['id' => $idPage]);
                }

                $projet2 = $repository->findBy(['id' => $idProjet]);
                $tag2 = implode(',', unserialize($projet2[0]->getTag()));

                return $this->render('projet/edit_proj.html.twig', [
                    'id' => $idPage,
                    'projet' => $projet2,
                    'tag' => $tag2,
                    'projForm' => $form->createView(),
                ]);
            }
        }
    }

/**
 * @Route("/search/{searchTerm}", name="search")
 */
public function search(string $searchTerm = "", Connection $connection): JsonResponse
{
    try {
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->select('COUNT(*) as total', 'nom')
            ->from('projets')
            ->where('nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->groupBy('nom');

        $projets = $queryBuilder->execute()->fetchAll();

        return $this->json($projets);
    } catch (\Exception $e) {
        $this->logger->error($e->getMessage());
        throw $e;
    }
}

}