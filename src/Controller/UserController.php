<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $nbResult = 5;

    /**
     * @Route("/users", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     */
    public function listAction(UserRepository $userRepository, Request $request)
    {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findUsers((int) $request->get('page', 1), $this->nbResult),
            'totalUser' => $userRepository->countUsers(),
            'nbResult' => $this->nbResult
            ]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @Route("/users/edit/{id}", name="user_edit")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function formAction(User $user = null, Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        if (!$user) {
            $user = new User();
        }

        $edit = $user->getId() !== null;
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            if ($edit) {
                $this->addFlash('success', "L'utilisateur a bien été modifié");
                return $this->redirectToRoute('user_list');
            }

            $this->addFlash('success', "L'utilisateur a bien été créé");

            if ($this->getUser() === null) {
                return $this->redirectToRoute('login');
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'edit' => $edit
        ]);
    }
}
