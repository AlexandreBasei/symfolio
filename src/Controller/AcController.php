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

    public function api(ManagerRegistry $doctrine, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'];
        $competence = $data['competence'];
        $niveau = $data['niveau'];
      
        $ac = new AC();

        $ac->setNom($nom);
        $ac->setCompetence($competence);
        $ac->setNiveau($niveau);

        $em = $doctrine->getManager();
        $em->persist($ac);
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
        $repository = $em->getRepository(AC::class);
        $ac = $repository->find($id);
    
        // Suppression du projet
        $em->remove($ac);
        $em->flush();

        $response = new Response();
        $response->setContent(json_encode(array('success' => true)));
        $response->headers->set('Content-Type', 'application/json');
      
        return $response;
    }
}
