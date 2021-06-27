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
}
