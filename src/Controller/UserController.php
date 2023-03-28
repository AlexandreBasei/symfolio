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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
            $id0 = $this->getUser()->getId();
            $role = $this->getUser()->getRoles();
        }

        if (!isset($role)){
            $role = [];
        }

        if ($this->getUser()->getId() == $id) {
            $monProfil = true;
        }

        if ($id == 0) {

            $em = $doctrine->getManager();
            $repository = $em->getRepository(User::class);
            $users = $repository->findBy(
                array('id' => $id0)
            );
        } 
        
        else {

            $em = $doctrine->getManager();
            $repository = $em->getRepository(User::class);
            $users = $repository->findBy(
                array('id' => $id)
            );

            if (!$users) {
                throw new NotFoundHttpException('L\'utilisateur demandé n\'existe pas.');
            }
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
            $nProj++;
        }

        $repository3 = $em->getRepository(Noter::class);
        $notes = $repository3->findAll();

        if (in_array("ROLE_PROF", $role)) {
            $noter = new Noter();

            $form = $this->createForm(NoterType::class, $noter,[
                'data' => [
                    'id' => $id,
                ]
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $noter->setIdUser($this->getUser());
                $noter->setIdProjet($form->get('idProjet')->getData());
                $noter->setNote($form->get('note')->getData());
                $noter->setCommentaire($form->get('commentaire')->getData());
                $em->persist($noter);
                $em->flush();
            }

            if (!isset($tag)){
                $tag = '';
            }

            if (!isset($monProfil)){
                $monProfil = false;
            }

            return $this->render(
                'user/profilProf.html.twig',
                array(
                    'users' => $users,
                    'projets' => $projets,
                    'notes' => $notes,
                    'tag' => $tag,
                    'monProfil' => $monProfil,
                    'noteForm' => $form->createView(),
                )
            );
        }

        else if (in_array("ROLE_ADMIN", $role)){
            $noter = new Noter();

            $form = $this->createForm(NoterType::class, $noter,[
                'data' => [
                    'id' => $id,
                ]
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $noter->setIdUser($this->getUser());
                $noter->setNote($form->get('note')->getData());
                $noter->setCommentaire($form->get('commentaire')->getData());
                $em->persist($noter);
                $em->flush();
            }



            if (!isset($tag)){
                $tag = '';
            }

            if (!isset($monProfil)){
                $monProfil = false;
            }

            return $this->render(
                'user/profilAdmin.html.twig',
                array(
                    'users' => $users,
                    'projets' => $projets,
                    'tag' => $tag,
                    'notes' => $notes,
                    'monProfil' => $monProfil,
                    'noteForm' => $form->createView(),
                )
            );
        }
        else {

            if (!isset($tag)){
                $tag = '';
            }

            if (!isset($monProfil)){
                $monProfil = false;
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
    }

    public function api(){
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

        $data = array('foo' => 'bar', 'baz' => 'qux');
        return $this->render('user/profil.html.twig', array('data' => $data));
    }
}