<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setIsActive(true);
            $user->setFreeCreations(3);
            $user->setAccountType($user->userAccountType["user"]);

            //TODO: create condition when email sending is implemented
            $user->setIsVerified(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

//            $signatureComponents = $this->verifyEmailHelper->generateSignature(
//                'registration_confirmation_route',
//                $user->getId(),
//                $user->getEmail()
//            );
//
//            $email = new TemplatedEmail();
//            $email->to($user->getEmail());
//            $email->htmlTemplate('registration/confirmation_email.html.twig');
//            $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
//
//            $this->mailer->send($email);

            // handle the user registration form and persist the new user...

//            $signatureComponents = $this->verifyEmailHelper->generateSignature(
//                'registration_confirmation_route',
//                $user->getId(),
//                $user->getEmail()
//            );

//            $email = new TemplatedEmail();
//            $email->to($user->getEmail());
//            $email->htmlTemplate('registration/confirmation_email.html.twig');
//            $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

//            $message = (new \Swift_Message())
//                ->setSubject('Inscription à Manabi')
//                ->setFrom('contact.manabi@gmail.com')
//                ->setTo($user->getEmail())
//                ->setBody(
//                    $this->renderView(
//                        'registration/confirmation_email.html.twig'
//                    )
//                )
//            ;
//
//            $mailer->send($message);

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
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->helper->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_dashboard');
    }
}
