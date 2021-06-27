<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

         if ($user)
         {
             return $this->redirectToRoute('dashboard');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/need_subscription", name="need_subscription")
     */
    public function needSubscription()
    {
        return $this->render('security/need_subscription.html.twig', [
            'title' => 'Renouveler votre abonnement',
        ]);
    }

    /**
     * @Route("/index", name="entry_point")
     * @Route("/")
     */
    public function welcomePage()
    {
        if($this->getUser())
        {
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('security/index.html.twig');
    }

    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer
    ): Response
    {
        //redirect user if he/she is logged in and wants to go on this page
        if ($this->getUser())
        {
            return $this->redirectToRoute('homepage');
        }

        if ($request->isMethod('POST'))
        {
            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */

            if ($user === null)
            {
                $this->addFlash('danger', 'Email inconnu');
                return $this->redirectToRoute('app_login');
            }
            $token = $tokenGenerator->generateToken();

            try
            {
                $user->setResetToken($token);
                $entityManager->flush();
            }
            catch (\Exception $e)
            {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
                ->from('contact@skorou.com')
                ->to($user->getEmail())
                ->htmlTemplate('emails/forgotten_password.html.twig')
                ->context([
                    'url' => $url
                ]);

            try
            {
                $mailer->send($email);
            }
            catch (TransportExceptionInterface $e)
            {
                throw new Exception("Sending mail failed");
            }

            $this->addFlash('notice', 'E-mail envoyé');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        //redirect user if he/she is logged in and wants to go on this page
        if ($this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST'))
        {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null)
            {
                $this->addFlash('danger', 'Token inconnu');
                return $this->redirectToRoute('app_login');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('app_login');
        }
        else
        {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
}
