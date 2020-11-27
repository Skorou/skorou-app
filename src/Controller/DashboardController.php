<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @Route("/")
     */
    public function index()
    {
        $user = $this->getUser();

        return $this->render('frontoffice/dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $user
        ]);
    }
}
