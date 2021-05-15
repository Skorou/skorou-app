<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

         if ($user)
         {
             return $this->redirectToRoute('dashboard');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/need_subscription", name="need_subscription")
     */
    public function needSubscription()
    {
        return $this->render('security/need_subscription.html.twig', [
            'title' => 'Renouveler votre abonnement',
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function welcomePage()
    {
        if($this->getUser())
        {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('security/index.html.twig');
    }
}
