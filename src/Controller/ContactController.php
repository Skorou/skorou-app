<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);

        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isSubmitted() && $form->isValid())
            {
                $email = (new TemplatedEmail())
                    ->from($form->get('email')->getData())
                    ->to('clara.tiare@live.fr') // TODO: replace by contact@skorou.com later
                    ->htmlTemplate('emails/contact.html.twig')
                    // pass variables (name => value) to the template
                    ->context([
                        'formData' => $form->getData()
                    ]);


                try
                {
                    $this->addFlash('success', 'Votre mail a bien été envoyé. Nous vous répondrons d\'ici peu !');

                    $mailer->send($email);
                }
                catch (TransportExceptionInterface $e)
                {
                    $this->addFlash('error', 'L\'envoi du mail a échoué');

                    throw new Exception("Sending mail failed");
                }

                return $this->redirectToRoute('contact');
            }
        }

        return $this->render('frontoffice/contact/contact.html.twig', [
            'controller_name' => 'ContactController',
            'title'           => 'Contact',
            'contactForm'     => $form->createView(),
        ]);
    }
}
