<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class VuePlaygroundController extends AbstractController
{
    /**
     * @Route("/vue")
     */
    public function vuePlayground()
    {
        return $this->render('vue_playground.html.twig');
    }
}