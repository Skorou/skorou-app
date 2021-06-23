<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Color;
use App\Entity\Font;
use App\Entity\Logo;
use App\Form\AddressFormType;
use App\Form\CharterColorType;
use App\Form\FontType;
use App\Form\LogoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    public function uploadLogo(Request $request,  SluggerInterface $slugger) : Response
    {
        $user = $this->getUser();

        $form = $this->createForm(LogoType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $logo = new Logo();

            $logoFile = $form->get('fileName')->getData();

            // this condition is needed because the 'logo' field is not required
            // so the image file must be processed only when a file is uploaded
            if ($logoFile)
            {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Move the file to the directory where brochures are stored
                try
                {
                    $logoFile->move(
                        $this->getParameter('logo_directory'),
                        $originalFilename
                    );
                } catch (FileException $e) {
                    throw $e;
                }

                // updates the 'logoFilename' property to store the image file name
                // instead of its contents
                $logo->setName($originalFilename);
                $logo->setUser($user);

                $this->entityManager->persist($logo);
                $this->entityManager->flush();

                return $this->redirectToRoute('color_choice');
            }
        }

        return $this->render('registration/account_creation/logo_upload.html.twig', [
            'controller_name' => 'AccountCreationController',
            'logoForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/register/account_creation/colors", name="color_choice")
     */
    public function chooseColor(Request $request) : Response
    {
        $user = $this->getUser();
        $form = $this->createForm(CharterColorType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $color1 = $form->get('color1')->getData();
            $color2 = $form->get('color2')->getData();
            $color3 = $form->get('color3')->getData();
            $hexaColors = [];
            array_push($hexaColors, $color1, $color2, $color3);

            foreach($hexaColors as $hexaColor)
            {
                $color = new Color();

                $color->setHexa($hexaColor);
                $color->setUser($user);

                $this->entityManager->persist($color);
                $this->entityManager->flush();
            }

            return $this->redirectToRoute('font_upload');
        }

        return $this->render('registration/account_creation/color_choice.html.twig', [
            'controller_name' => 'AccountCreationController',
            'colorForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/register/account_creation/fonts", name="font_upload")
     */
    public function uploadFont(Request $request, SluggerInterface $slugger) : Response
    {
        $form = $this->createForm(FontType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $font = new Font();

            $fontFile = $form->get('fileName')->getData();
            $fontName = $form->get('fontName')->getData();

            // this condition is needed because the 'logo' field is not required
            // so the image file must be processed only when a file is uploaded
            if ($fontFile)
            {
                $originalFilename = pathinfo($fontFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Move the file to the directory where brochures are stored
                try
                {
                    $fontFile->move(
                        $this->getParameter('font_directory'),
                        $originalFilename
                    );
                } catch (FileException $e) {
                    throw $e;
                }

                // updates the 'logoFilename' property to store the image file name
                // instead of its contents
                $font->setFile($originalFilename);
                $font->setName($fontName);

                $this->entityManager->persist($font);
                $this->entityManager->flush();
            }
        }

        return $this->render('registration/account_creation/font_upload.html.twig', [
            'controller_name' => 'AccountCreationController',
            'fontForm' => $form->createView()
        ]);
    }
}
