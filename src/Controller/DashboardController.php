<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @Route("/")
     */
    public function index()
    {
        $user = $this->getUser();

        if(!$user->isActive())
        {
            return $this->redirectToRoute('need_subscription');
        }

        return $this->render('frontoffice/dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user,
            'title' => 'Dashboard'
        ]);
    }
}
