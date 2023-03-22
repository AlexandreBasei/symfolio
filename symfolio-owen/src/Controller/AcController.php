<?php

namespace App\Controller;

use App\Entity\AC;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AcType;

class AcController extends AbstractController
{
    #[Route('/ac', name: 'app_ac')]
    public function index(): Response
    {
        return $this->render('ac/ac_add.html.twig', [
            'controller_name' => 'AcController',
        ]);
    }

    public function add(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $ac = new AC();

        $form = $this->createForm(AcType::class, $ac);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->persist($ac);
            $em->flush();
        }

        return $this->render('ac/ac_add.html.twig', [
            'acForm' => $form->createView(),
        ]);
    }
}
