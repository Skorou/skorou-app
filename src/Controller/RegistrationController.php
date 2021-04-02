<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * List all errors of a given bound form.
     *
     *
     * @return array
     */
    public function getFormErrors($form)
    {
        $errors = array();

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child /** @var Form $child */) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param MailerInterface $mailer
     * @return Response
     * @throws Exception
     */
    public function registerAjax(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, MailerInterface $mailer) : Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('dashboard');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit(json_decode($request->getContent()));
        $requestContent = $request->getContent();
//        $form->handleRequest($request);

        if(!$form->isValid())
        {
//            return new JsonResponse($this->getFormErrors($form), 400);
            $errors = new JsonResponse($this->getFormErrors($form), 400);

            var_dump($errors);
        }
        else
        {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setIsActive(true);
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
                //delete user to allow him to retry registering
                $this->entityManager->remove($user);
                $this->entityManager->flush();

                throw new Exception("Sending mail failed:" . $e);
            }

            $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

            $response = new JsonResponse();

//            return $response;
        }

        return $this->render('registration/register.html.twig');
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
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

        return $this->redirectToRoute('dashboard');
    }

    /**
     * @Route("/wait_verify_email", name="wait_verify_email")
     */
    public function waitVerifyEmail()
    {
        $user = $this->getUser();

        if($user->isVerified())
        {
            $this->addFlash('success', 'Your email address has been verified.');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('registration/verify_email.html.twig');
    }
}