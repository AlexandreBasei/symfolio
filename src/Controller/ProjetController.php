<?php
namespace App\Controller;

use App\Entity\Projets;
use App\Form\ProjetAddType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Image;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\ImageType;
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
        return $this->render('projet/accueil_proj.html.twig');
    }

    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->getUser()->getId();

        $projet = new Projets();

        $form = $this->createForm(ProjetAddType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $projet->setIdUser($user);

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
                $image->setFichier("images/" . $newFilename);
            }
            $em = $doctrine->getManager();
            $em->persist($image);
            $em->flush(); //C'est là qu'est insérée l'image
        }

        return $this->render('projet/add_proj.html.twig');
    }
}