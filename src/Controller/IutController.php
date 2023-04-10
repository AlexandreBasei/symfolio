<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Iut;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\IutType;

class IutController extends AbstractController
{
    #[Route('/iut', name: 'app_iut')]
    public function index(): Response
    {
        return $this->render('iut/index.html.twig', [
            'controller_name' => 'IutController',
        ]);
    }

    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $iut = new Iut();

        $form = $this->createForm(IutType::class, $iut);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($iut);
            $em->flush(); //C'est là qu'est inséré l'IUT

            $this->addFlash('success', 'L\'IUT a été ajouté avec succès.');
            return $this->render('projet/accueil_proj.html.twig');
        }

        return $this->render('iut/add.html.twig', [
            'iutForm' => $form->createView(),
        ]);
    }

    public function api(ManagerRegistry $doctrine, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'];
      
        $iut = new IUT();

        $iut->setNom($nom);

        $em = $doctrine->getManager();
        $em->persist($iut);
        $em->flush();
      
        $response = new Response();
        $response->setContent(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');
      
        return $response;
    }

    public function apidel(ManagerRegistry $doctrine, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $em = $doctrine->getManager();
        $repository = $em->getRepository(Iut::class);
        $iut = $repository->find($id);
    
        // Suppression du projet
        $em->remove($iut);
        $em->flush();

        $response = new Response();
        $response->setContent(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');
      
        return $response;
    }
}
