<?php

namespace App\Controller;

use App\Entity\Noter;
use App\Entity\Projets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function profil($id, ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        if ($id == 0) {

            $id = $this->getUser()->getId();
            $em = $doctrine->getManager();
            $repository = $em->getRepository(User::class);
            $users = $repository->findBy(
                array('id' => $id)
            );
        } else {

            $em = $doctrine->getManager();
            $repository = $em->getRepository(User::class);
            $users = $repository->findBy(
                array('id' => $id)
            );
        }

        $repository2 = $em->getRepository(Projets::class);
        $projets = $repository2->findBy(
            array('idUser' => $id)
        );

        foreach ($projets as $projet) {
            $tag = $projet->getTag();
            $tag = unserialize($tag);
            $tag = implode(" ", $tag);
        }

        return $this->render(
            'user/profil.html.twig',
            array(
                'users' => $users,
                'projets' => $projets,
                'tag' => $tag,
            )
        );
    }

    public function apiAction($id, ManagerRegistry $doctrine, Security $security): Response
    {
        $em = $doctrine->getManager();
        $repository = $em->getRepository(User::class);
        $users = $repository->findBy(
            array('id' => $id)
        );

        $data = array();
        foreach ($users as $user) {
            $data = array(
                'email' => $user->getUserIdentifier(),
                'iut' => $user->getIut(),
                'niveau' => $user->getNiveau(),
                'photo' => $user->getPhoto(),
                'descrip^tion' => $user->getDescription()
            );
        }
        return $this->render('user/profilProf.html.twig', $data);
    }

}