<?php

namespace App\Controller;

use App\Entity\Noter;
use App\Entity\Projets;
use App\Entity\AC;
use App\Entity\Iut;
use App\Form\AcType;
use App\Form\IutType;
use App\Form\DeleteAcType;
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
        $role = [];
        $id0 = 0;
        $tag = '';
        $monProfil = false;
        $acId = "Ce projet n'est associé à aucun apprentissage critique";

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
            $id0 = $this->getUser()->getId();
            $role = $this->getUser()->getRoles();
        }

        else {
            return $this->redirectToRoute('app_login');
        }

        if ($id != 0 && $id0 == $id){
            $monProfil = true;
        }

        if ($id == 0) {

            $em = $doctrine->getManager();
            $repository = $em->getRepository(User::class);
            $users = $repository->findBy(
                array('id' => $id0)
            );

            $id = $id0;
            $monProfil = true;
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

        foreach ($projets as $projet) {
            $tag = $projet->getTag();
            $tag = unserialize($tag);
            $tag = implode(" ", $tag);

            $acId = $projet->getIdAC();
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

            return $this->render(
                'user/profilProf.html.twig',
                array(
                    'users' => $users,
                    'projets' => $projets,
                    'notes' => $notes,
                    'tag' => $tag,
                    'acs' => $acId,
                    'monProfil' => $monProfil,
                    'id0' => $id0,
                    'id' => $id,
                    'noteForm' => $form->createView()
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

            foreach ($projets as $projet) {
                $tag = $projet->getTag();
                $tag = unserialize($tag);
                $tag = implode(" ", $tag);
    
                $acId = $projet->getIdAC();
            }

            return $this->render(
                'user/profilAdmin.html.twig',
                array(
                    'users' => $users,
                    'projets' => $projets,
                    'tag' => $tag,
                    'notes' => $notes,
                    'monProfil' => $monProfil,
                    'acs' => $acId,
                    'id0' => $id0,
                    'id' => $id,
                    'noteForm' => $form->createView(),
                )
            );
        }
        else {

            return $this->render(
                'user/profil.html.twig',
                array(
                    'users' => $users,
                    'projets' => $projets,
                    'acs' => $acId,
                    'notes' => $notes,
                    'tag' => $tag,
                    'monProfil' => $monProfil,
                    'id' => $id,
                )
            );
        }
    }

    public function delete_comment($idNote, $idUser, $idPage, ManagerRegistry $doctrine, Security $security): Response{

        $role = [];

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
            $id0 = $this->getUser()->getId();
            $role = $this->getUser()->getRoles();

            if ($id0 == $idUser || in_array("ROLE_ADMIN", $role)) //On vérifie que c'est bien l'utilisateur connecté qui veut supprimer un commentaire
            {
                $em = $doctrine->getManager();
                $repository = $em->getRepository(Noter::class);
                $note = $repository->find($idNote);
            
                $em->remove($note);
                $em->flush();
            }
        }

        return $this->redirectToRoute('profil', ['id' => $idPage]);

    }

        public function adminpanel(Request $request, ManagerRegistry $doctrine, Security $security): Response
        {

            $role = [];
            $acdelete = 0;

            if ($security->isGranted('IS_AUTHENTICATED_FULLY')){
                $role = $this->getUser()->getRoles();

                if (in_array("ROLE_ADMIN", $role))
                {

                    $em = $doctrine->getManager();
                    $repository = $em->getRepository(AC::class);
                    $ac = $repository->findAll();

                    $repository2 = $em->getRepository(Iut::class);
                    $iut = $repository2->findAll();

                    // $iut = new Iut();

                    // $form3 = $this->createForm(IutType::class, $iut);
                    // $form3->handleRequest($request);
            
                    // if ($form3->isSubmitted()) {
                    //     $em = $doctrine->getManager();
                    //     $em->persist($iut);
                    //     $em->flush(); //C'est là qu'est inséré l'IUT
                    // }
                }
            }

            return $this->render(
                'user/adminpanel.html.twig',
                array(
                    'acs' => $ac,
                    'iuts' => $iut,
                )
            );
        }
}