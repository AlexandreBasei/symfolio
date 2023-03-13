<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function profil($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(User::class);
        $users = $repository->findBy(
            array('id' => $id)
        );

        return $this->render('user/profil.html.twig', array(
            'users' => $users,
        ));
    }
}
