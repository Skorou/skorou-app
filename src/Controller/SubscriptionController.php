<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @Route("/subscription", name="subscription")
     */
    public function index()
    {
        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }

    /**
     * @Route("/register/subscribe", name="subscriptions", methods={"GET","POST"})
     * @param Request $request
     */
    public function subscriptions(Request $request)
    {
        $subscriptions = Subscription::SUBSCRIPTION;

        return $this->render('subscription/subscription.html.twig', [
            'controller_name' => 'RegistrationController',
            'subscriptions' => $subscriptions,
            'title' => 'Choix de l\'abonnement'
        ]);
    }

    /**
     * @Route("/register/subscription_payment", name="subscription_payment", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function subscriptionPayment(Request $request)
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey('sk_test_51IjOenKmSLmNLJ03EWVJGLjidQzNbNUQuCBcxSnoA8GHtU8NoNlpzAjrjC2ZrFc21vOYe4BWrrdPUycLSWdRTXx700NhERDdnv');

        $subscriptionId = $request->get('subscriptionId');
        $subscriptions = Subscription::SUBSCRIPTION;
        $subscriptionAmount = null;
        $subscriptionDuration = null;

        for($i = 0 ; $i < count($subscriptions) ; $i++)
        {
            if($subscriptionId === $subscriptions[$i]['id'])
            {
                $subscriptionAmount   = ($subscriptions[$i]['salePrice'] !== 0) ? $subscriptions[$i]['salePrice'] : $subscriptions[$i]['regularPrice'];
                $subscriptionDuration = $subscriptions[$i]['duration'];
            }
        }

        if($subscriptionAmount && $subscriptionDuration)
        {
            if ($request->isMethod('POST'))
            {
                try
                {
                    $charge = \Stripe\Charge::create([
                        'amount' => $subscriptionAmount * 100,
                        'currency' => 'eur',
                        'description' => 'Subscription payment',
                        'source' => $request->request->get('stripeToken'),
                    ]);

                    $subscription = new Subscription();
                    $user = $this->getUser();

                    $subscription->setUser($user);
                    $startDate = new \DateTime('@'.strtotime('now'));
                    $endDate = new \DateTime('@'.strtotime('now'));
                    $endDate->add(new \DateInterval('P' . $subscriptionDuration . 'M'));
                    $subscription->setStartDate($startDate);
                    $subscription->setEndDate($endDate);
                    $subscription->setPrice($subscriptionAmount);
                    $user->setIsActive(true);

                    //TODO: set user address in subscription
//                    $subscription->setAddress();

                    $this->entityManager->persist($subscription);
                    $this->entityManager->flush();

                    return $this->redirectToRoute('account_configuration');
                }
                catch(\Stripe\Exception\CardException $e)
                {
                    // Since it's a decline, \Stripe\Exception\CardException will be caught
                    error_log('Status is:' . $e->getHttpStatus() . '\n');
                    error_log('Type is:' . $e->getError()->type . '\n');
                    error_log('Code is:' . $e->getError()->code . '\n');
                    // param is '' in this case
                    error_log('Param is:' . $e->getError()->param . '\n');
                    error_log('Message is:' . $e->getError()->message . '\n');
                }
                catch (\Stripe\Exception\RateLimitException $e)
                {
                    // Too many requests made to the API too quickly
                    error_log('Status is:' . $e->getHttpStatus() . '\n');
                    error_log('Type is:' . $e->getError()->type . '\n');
                    error_log('Code is:' . $e->getError()->code . '\n');
                    // param is '' in this case
                    error_log('Param is:' . $e->getError()->param . '\n');
                    error_log('Message is:' . $e->getError()->message . '\n');
                }
                catch (\Stripe\Exception\InvalidRequestException $e)
                {
                    // Invalid parameters were supplied to Stripe's API
                    error_log('Status is:' . $e->getHttpStatus() . '\n');
                    error_log('Type is:' . $e->getError()->type . '\n');
                    error_log('Code is:' . $e->getError()->code . '\n');
                    // param is '' in this case
                    error_log('Param is:' . $e->getError()->param . '\n');
                    error_log('Message is:' . $e->getError()->message . '\n');
                }
                catch (\Stripe\Exception\AuthenticationException $e)
                {
                    // Authentication with Stripe's API failed
                    // (maybe you changed API keys recently)
                    error_log('Status is:' . $e->getHttpStatus() . '\n');
                    error_log('Type is:' . $e->getError()->type . '\n');
                    error_log('Code is:' . $e->getError()->code . '\n');
                    // param is '' in this case
                    error_log('Param is:' . $e->getError()->param . '\n');
                    error_log('Message is:' . $e->getError()->message . '\n');
                }
                catch (\Stripe\Exception\ApiConnectionException $e)
                {
                    // Network communication with Stripe failed
                    error_log('Status is:' . $e->getHttpStatus() . '\n');
                    error_log('Type is:' . $e->getError()->type . '\n');
                    error_log('Code is:' . $e->getError()->code . '\n');
                    // param is '' in this case
                    error_log('Param is:' . $e->getError()->param . '\n');
                    error_log('Message is:' . $e->getError()->message . '\n');
                }
                catch (\Stripe\Exception\ApiErrorException $e)
                {
                    // Display a very generic error to the user, and maybe send
                    // yourself an email
                    error_log('Status is:' . $e->getHttpStatus() . '\n');
                    error_log('Type is:' . $e->getError()->type . '\n');
                    error_log('Code is:' . $e->getError()->code . '\n');
                    // param is '' in this case
                    error_log('Param is:' . $e->getError()->param . '\n');
                    error_log('Message is:' . $e->getError()->message . '\n');
                }
                catch (Exception $e)
                {
                    // Something else happened, completely unrelated to Stripe
                    error_log($e);
                }

            }
        }

        return $this->render('subscription/subscription_payment.html.twig', [
            'controller_name' => 'RegistrationController',
            'title' => 'Paiement'
        ]);
    }
}
