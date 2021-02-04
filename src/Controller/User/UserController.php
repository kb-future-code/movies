<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User\User;
use App\Form\UserRegistrationType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/user/login", name="user_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted(User::MAIN_ROLE)) {
            throw $this->createAccessDeniedException('You are already logged in!');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/user/register", name="user_register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isGranted(User::MAIN_ROLE)) {
            throw $this->createAccessDeniedException('You are already logged in!');
        }

        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'flash_msg.success_account_create');

            return $this->redirectToRoute('app_app_index');
        }

        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/user/show", name="user_show")
     *
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        return $this->render('user/show.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @Route("/user/edit", name="user_edit")
     *
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request): Response
    {
        $this->denyAccessUnlessGranted(User::MAIN_ROLE);

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'flash_msg.success_account_edit');

            return $this->redirectToRoute('app_app_index');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView()]);
    }
}
