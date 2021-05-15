<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription", name="subscription")
     */
    public function index()
    {
        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    public function getSubscriptions()
    {
        //TODO: manage to get this subscriptions array directly with class attribute
        $subscriptions = [
            [
                'id'           => 1,
                'length'       => 1,
                'regularPrice' => 20,
                'salePrice'    => 20
            ],
            [
                'id'           => 2,
                'length'       => 3,
                'regularPrice' => 60,
                'salePrice'    => 55
            ],
            [
                'id'           => 3,
                'length'       => 6,
                'regularPrice' => 120,
                'salePrice'    => 105
            ],
            [
                'id'           => 4,
                'length'       => 12,
                'regularPrice' => 240,
                'salePrice'    => 200
            ],
        ];

        return $subscriptions;
    }

    /**
     * @Route("/register/subscribe", name="subscriptions", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function subscriptions(Request $request)
    {
        $subscriptions = $this->getSubscriptions();

        if($request->isMethod('POST'))
        {
            $amount = $request->request->get('amount');

//            $this->redirectToRoute(
//                'subscription_payment',
//                array('subscriptionAmount' => $amount),
//                307
//            );

            $response = $this->forward(
                'App\Controller\SubscriptionController::subscriptionPayment', [
                'subscriptionAmount'  => $amount,
            ]);

            return $response;
        }

        return $this->render('registration/subscription.html.twig', [
            'controller_name' => 'RegistrationController',
            'subscriptions' => $subscriptions,
            'title' => 'Choix de l\'abonnement'
        ]);
    }

    /**
     * @Route("/register/subscription_payment", name="subscription_payment", methods={"POST"})
     * @param Request $request
     * @param float $subscriptionAmount
     * @return Response
     */
    public function subscriptionPayment(Request $request, float $subscriptionAmount)
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey('sk_test_51IjOenKmSLmNLJ03EWVJGLjidQzNbNUQuCBcxSnoA8GHtU8NoNlpzAjrjC2ZrFc21vOYe4BWrrdPUycLSWdRTXx700NhERDdnv');

//        $subscriptionAmount = $request->get('subscriptionAmount');

        if ($request->isMethod('POST'))
        {
            try
            {
                $charge = \Stripe\Charge::create([
                    'amount' => $subscriptionAmount * 100,
                    'currency' => 'eur',
                    'description' => 'Example charge',
                    'source' => $request->request->get('stripeToken'),
                ]);

//                $subscription = new Subscription();
//                $user = $this->getUser();
//
//                //TODO: set user address in subscription
////                $subscription->setAddress();
//
//                $this->entityManager->persist($user);
//                $this->entityManager->flush();
            }
            catch(\Stripe\Exception\CardException $e)
            {
                // Since it's a decline, \Stripe\Exception\CardException will be caught
                echo 'Status is:' . $e->getHttpStatus() . '\n';
                echo 'Type is:' . $e->getError()->type . '\n';
                echo 'Code is:' . $e->getError()->code . '\n';
                // param is '' in this case
                echo 'Param is:' . $e->getError()->param . '\n';
                echo 'Message is:' . $e->getError()->message . '\n';
            }
            catch (\Stripe\Exception\RateLimitException $e)
            {
                // Too many requests made to the API too quickly
                echo 'Status is:' . $e->getHttpStatus() . '\n';
                echo 'Type is:' . $e->getError()->type . '\n';
                echo 'Code is:' . $e->getError()->code . '\n';
                // param is '' in this case
                echo 'Param is:' . $e->getError()->param . '\n';
                echo 'Message is:' . $e->getError()->message . '\n';
            }
            catch (\Stripe\Exception\InvalidRequestException $e)
            {
                // Invalid parameters were supplied to Stripe's API
                echo 'Status is:' . $e->getHttpStatus() . '\n';
                echo 'Type is:' . $e->getError()->type . '\n';
                echo 'Code is:' . $e->getError()->code . '\n';
                // param is '' in this case
                echo 'Param is:' . $e->getError()->param . '\n';
                echo 'Message is:' . $e->getError()->message . '\n';
            }
            catch (\Stripe\Exception\AuthenticationException $e)
            {
                // Authentication with Stripe's API failed
                // (maybe you changed API keys recently)
                echo 'Status is:' . $e->getHttpStatus() . '\n';
                echo 'Type is:' . $e->getError()->type . '\n';
                echo 'Code is:' . $e->getError()->code . '\n';
                // param is '' in this case
                echo 'Param is:' . $e->getError()->param . '\n';
                echo 'Message is:' . $e->getError()->message . '\n';
            }
            catch (\Stripe\Exception\ApiConnectionException $e)
            {
                // Network communication with Stripe failed
                echo 'Status is:' . $e->getHttpStatus() . '\n';
                echo 'Type is:' . $e->getError()->type . '\n';
                echo 'Code is:' . $e->getError()->code . '\n';
                // param is '' in this case
                echo 'Param is:' . $e->getError()->param . '\n';
                echo 'Message is:' . $e->getError()->message . '\n';
            }
            catch (\Stripe\Exception\ApiErrorException $e)
            {
                // Display a very generic error to the user, and maybe send
                // yourself an email
                echo 'Status is:' . $e->getHttpStatus() . '\n';
                echo 'Type is:' . $e->getError()->type . '\n';
                echo 'Code is:' . $e->getError()->code . '\n';
                // param is '' in this case
                echo 'Param is:' . $e->getError()->param . '\n';
                echo 'Message is:' . $e->getError()->message . '\n';
            }
            catch (Exception $e)
            {
                // Something else happened, completely unrelated to Stripe
                echo $e;
            }

        }

        return $this->render('registration/subscription_payment.html.twig', [
            'controller_name' => 'RegistrationController',
            'title' => 'Paiement'
        ]);
    }
}
