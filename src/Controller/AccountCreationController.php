<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Logo;
use App\Form\AddressFormType;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class AccountCreationController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @Route("/register/account_creation/configuration", name="account_configuration")
     */
    public function configureAccount(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(AddressFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $address = new Address();
            $data = $form->getData();
            $address->setAddress1($data['address1']);
            $address->setAddress2($data['address2']);
            $address->setZipcode($data['zipcode']);
            $address->setCity($data['city']);
            $address->setCountry($data['country']);
            $address->setUser($user);
            $user->setWebsite($data['website']);
            $user->setFaxNumber($data['faxNumber']);
            $user->setPhoneNumber($data['phoneNumber']);
            $user->setFirstname($data['firstname']);
            $user->setLastname($data['lastname']);
            $user->setCompanyStatus($data['companyStatus']);
            $user->setCapital($data['capital']);
            $user->setApeCode($data['apeCode']);
            $user->setSiret($data['siret']);
            $user->setSiren($data['siren']);

            $this->entityManager->persist($address);
            $this->entityManager->flush();

            return $this->redirectToRoute('logo_upload');
        }

        return $this->render('registration/account_creation/account_configuration.html.twig', [
            'controller_name' => 'AccountCreationController',
            'addressForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/register/account_creation/logo", name="logo_upload")
     */
    public function uploadLogo(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UploadType::class);

//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid())
//        {
//            $logo = new Logo();
//            $data = $form->getData();
//            $logo->setBrochureFilename($data['address1']);
//            $logo->setUser($user);
//
//            $this->entityManager->persist($logo);
//            $this->entityManager->flush();
//        }

        return $this->render('registration/account_creation/logo_upload.html.twig', [
            'controller_name' => 'AccountCreationController',
            'logoForm' => $form->createView()
        ]);
    }
}
