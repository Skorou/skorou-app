<?php

namespace App\Controller\Backoffice;

use App\Entity\Creation;
use App\Entity\CreationType;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeController extends AbstractDashboardController
{
    /**
     * @Route("/backoffice")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(CreationCrudController::class)->generateUrl());

//        // you can also redirect to different pages depending on the current user
//        if ('jane' === $this->getUser()->getUsername()) {
//            return $this->redirect('...');
//        }
//
//        // you can also render some template to display a proper Dashboard
//        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
//        return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // you can include HTML contents too (e.g. to link to an image)
            ->setTitle('<img src="assets/images/logo_skorou.jpg" class="navbar-logo" alt="logo Skorou"> Skorou')

            // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('favicon.svg')

            // the domain used by default is 'messages'
            ->setTranslationDomain('my-custom-domain')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')
            ;
    }

    public function configureAssets(): Assets
    {
        $assets = Assets::new();

        return $assets
            ->addCssFile('css/backoffice.css')
            ->addCssFile('bundles/easyadmin/app.css')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Backoffice', 'fa fa-home'),
            MenuItem::linktoRoute('Frontoffice', 'fa fa-home', 'dashboard'),

            MenuItem::section('Templates'),
            MenuItem::linkToCrud('Types de catégorie', 'fa fa-tags', CreationType::class),
            MenuItem::linkToCrud('Créations', 'fa fa-file', Creation::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)
        ];
    }
}