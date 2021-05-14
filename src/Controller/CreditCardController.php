<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CreditCardController extends AbstractController
{
    public function updateCreditCardAction(Request $request)
    {
        $token = $request->request->get('stripeToken');
        $user = $this->getUser();
        $stripeClient = $this->get('stripe_client');
    }

}