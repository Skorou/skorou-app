<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\User;
use App\Form\AddressFormType;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, MailerInterface $mailer): Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('dashboard');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setIsActive(false);
            $user->setFreeCreations(3);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                $user->getId(),
                $user->getEmail()
            );

            $email = (new TemplatedEmail())
                ->from('contact@skorou.com')
                ->to($user->getEmail())
                ->htmlTemplate('emails/signup.html.twig')
                // pass variables (name => value) to the template
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => $user->getUsername(),
                    'signedUrl' => $signatureComponents->getSignedUrl()
                ]);


            try
            {
                $mailer->send($email);
            }
            catch (TransportExceptionInterface $e)
            {
                throw new Exception("Sending mail failed");
            }

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $user = $this->getUser();

        // validate email confirmation link, sets User::isVerified=true and persists
        try
        {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
            $user->setRoles(array('ROLE_USER'));
            $user->setIsVerified(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        catch (VerifyEmailExceptionInterface $exception)
        {
            $this->addFlash('error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('welcome_user');
    }

    /**
     * @Route("/register/wait_verify_email", name="wait_verify_email")
     */
    public function waitVerifyEmail()
    {
        $user = $this->getUser();

        if($user->isVerified())
        {
            $this->addFlash('success', 'Your email address has been verified.');

            return $this->redirectToRoute('welcome_user');
        }

        return $this->render('registration/verify_email.html.twig');
    }

    /**
     * @Route("/register/welcome", name="welcome_user")
     */
    public function welcomeUser()
    {
        $user = $this->getUser();

        return $this->render('registration/welcome.html.twig', [
            'controller_name' => 'RegistrationController',
            'user' => $user,
            'title' => 'Welcome'
        ]);
    }

    /**
     * @Route("/register/address", name="address")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function address(Request $request)
    {
////        $user = $this->getUser();
//        //TODO: what ? une adresse est forcément liée à une souscription ??
//        $address = new Address($subscriptions);
//
//        $form = $this->createForm(AddressFormType::class, $address);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid())
//        {
////            $user->setIsActive(false);
////            $user->setFreeCreations(3);
////
////            $this->entityManager->persist($user);
////            $this->entityManager->flush();
//
//            return $this->redirectToRoute('subscriptions');
//        }
//
//        return $this->render('registration/address.html.twig', [
//            'addressForm' => $form->createView(),
//            'controller_name' => 'RegistrationController',
//        ]);
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

        return $this->render('registration/subscription.html.twig', [
            'controller_name' => 'RegistrationController',
            'subscriptions' => $subscriptions,
            'title' => 'Choix de l\'abonnement'
        ]);
    }

    /**
     * @Route("/register/subscription_payment", name="subscription_payment", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function subscriptionPayment(Request $request)
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey('sk_test_51IjOenKmSLmNLJ03EWVJGLjidQzNbNUQuCBcxSnoA8GHtU8NoNlpzAjrjC2ZrFc21vOYe4BWrrdPUycLSWdRTXx700NhERDdnv');

        // Token is created using Stripe Checkout or Elements!
        // Get the payment token ID submitted by the form:
//        $token = $_POST['stripeToken'];

        $subscriptionAmount = $request->get('subscriptionAmount');

        if ($request->isMethod('post'))
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
