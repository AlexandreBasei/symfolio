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
use App\Form\NoterType;
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

    public function getUserId(Security $security)
    {
        $user = $security->getUser();
        
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }
        
        return new JsonResponse(['id' => $user->getId()]);
    }

    public function profil($id, ManagerRegistry $doctrine, Request $request, Security $security): Response
    {
        $id0 = $this->getUser()->getId();
        $role = $this->getUser()->getRoles();

        if ($id == 0) {

            $em = $doctrine->getManager();
            $repository = $em->getRepository(User::class);
            $users = $repository->findBy(
                array('id' => $id0)
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

        $nProj = 0;

        foreach ($projets as $projet) {
            $tag = $projet->getTag();
            $tag = unserialize($tag);
            $tag = implode(" ", $tag);
            $idProj = $projet->getId();
            $nProj++;
        }



        if (in_array("ROLE_PROF", $role) || in_array("ROLE_ADMIN", $role)) {
            $noter = new Noter();

            $form = $this->createForm(NoterType::class, $noter, [
                'current_user' => $this->getUser(),
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($noter);
                $em->flush();
            }

            if (!isset($tag)){
                $tag = '';
            }

            return $this->render(
                'user/profilProf.html.twig',
                array(
                    'users' => $users,
                    'projets' => $projets,
                    'tag' => $tag,
                    'noteForm' => $form->createView(),
                )
            );
        }
        else{

            if (!isset($tag)){
                $tag = '';
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

        // $em = $doctrine->getManager();
        // $repository = $em->getRepository(User::class);
        // $users = $repository->findBy(
        //     array('id' => $id)
        // );

        // $repository2 = $em->getRepository(Projets::class);
        // $projets = $repository2->findBy(
        //     array('idUser' => $id)
        // );

        // $data = array();
        // foreach ($users as $user) {
        //     foreach ($projets as $projet) {
        //         $tag = $projet->getTag();
        //         $tag = unserialize($tag);
        //         $tag = implode(" ", $tag);
        //         $data = array(
        //             'email' => $user->getUserIdentifier(),
        //             'iut' => $user->getIut(),
        //             'niveau' => $user->getNiveau(),
        //             'photo' => $user->getPhoto(),
        //             'description' => $user->getDescription(),
        //             'pNom' => $projet->getNom(),
        //             'pDesc' => $projet->getDescription(),
        //             'pImage' => $projet->getImage(),
        //             'pTag' => $tag,
        //             'pDate' => $projet->getDatePubli()
        //         );
        //     }
        // }
        // return new JsonResponse($data);
    }
}