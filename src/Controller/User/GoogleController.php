<?php

namespace App\Controller\User;

use App\Entity\User\User;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/user/connect/google", name="user_connect_google_start")
     *
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function connectGoogleAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        if ($this->isGranted(User::MAIN_ROLE)) {
            throw $this->createAccessDeniedException('You are already logged in!');
        }

        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'profile',
                'email'
            ], []);
    }

    /**
     * Redirect back from google, configured in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/user/connect/google/check", name="user_connect_google_check")
     *
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function connectGoogleCheckAction(Request $request, ClientRegistry $clientRegistry): RedirectResponse
    {
        $this->addFlash('success', 'flash_msg.success_login');
        return $this->redirectToRoute('app_app_index');
    }
}