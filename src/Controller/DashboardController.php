<?php

namespace App\Controller;

use App\Entity\Template;
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

    /**
     * @Route("/dashboard/creations", name="generate_creation")
     */
    public function generateCreations()
    {
        $templates = $this->getDoctrine()
            ->getRepository(Template::class)
            ->findAll();
        $user = $this->getUser();
        $mergedTemplates = array_map(function($template) use ($user) {
            return $template->mergeFields($user);
        }, $templates);

        return $this->render('frontoffice/dashboard/creations.html.twig', [
            'templates' => $mergedTemplates,
            'user' => $user,
            'title' => 'Génération de créations'
        ]);
    }
}
