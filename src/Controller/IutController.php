<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Iut;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

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
        }

        return $this->render('iut/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
