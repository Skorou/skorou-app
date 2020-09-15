<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Color;
use App\Entity\Creation;
use App\Entity\CreationCategory;
use App\Entity\CreationType;
use App\Entity\CreditCard;
use App\Entity\Folder;
use App\Entity\Font;
use App\Entity\FontUser;
use App\Entity\Image;
use App\Entity\ImageUploaded;
use App\Entity\Logo;
use App\Entity\Subscription;
use App\Entity\Template;
use App\Entity\TemplateForm;
use App\Entity\TemplateFormField;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
require_once 'vendor/autoload.php';

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++)
        {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($faker->password);
            $user->setAccountType($faker->randomDigit);
            $user->setCompanyName($faker->company);
            $user->setCompanyPicture($faker->imageUrl);
            $user->setFreeCreations($faker->numberBetween(0, 20));
            $manager->persist($user);

            // create 20 addresses
            $address = new Address();
            $address->setUser($user);
            $address->setAddress1($faker->streetAddress);
            $address->setAddress2($faker->secondaryAddress);
            $address->setCity($faker->city);
            $address->setZipcode($faker->postcode);
            $address->setCountry($faker->country);
            $address->setFirstName($faker->firstName);
            $address->setLastName($faker->lastName);
            $address->setIsFavorite($faker->boolean);
            $manager->persist($address);

            // create 20 colors
            $color = new Color();
            $color->setUser($user);
            $color->setHexa(ltrim($faker->hexcolor, '#'));
            $manager->persist($color);

            // create 20 creations
            $creation = new Creation();
            $creation->setUser($user);;
            $creation->setName($faker->word);
            $manager->persist($creation);

            // create 20 creation categories
            $creationCategory = new CreationCategory();
            $creationCategory->setName($faker->word);
            $creationCategory->setOrderIndex($i);
            $manager->persist($creationCategory);

            // create 20 creation types
            $creationType = new CreationType();
            $creationType->setName($faker->word);
            $creationType->setOrderIndex($i);
            $creationType->setHeight($faker->numberBetween(200, 1500));
            $creationType->setWidth($faker->numberBetween(200, 1500));
            $manager->persist($creationType);

            // create 20 credit cards
            $creditCard = new CreditCard();
            $creditCard->setUser($user);
            $creditCard->setNumber($faker->creditCardNumber);
            $creditCard->setExpiryMonth($faker->month(12));
            $creditCard->setExpiryYear($faker->year(2050));
            $creditCard->setCvv($faker->numberBetween(100, 999));
            $creditCard->setIsFavorite($faker->boolean);
            $manager->persist($creditCard);

            // create 20 folders
            $folder = new Folder();
            $folder->setName($faker->word);
            $manager->persist($folder);

            // create 20 fonts
            $font = new Font();
            $font->setName($faker->word);
            $font->setFile($faker->word . '.ttf');
            $manager->persist($font);

            // create 20 font users
            $fontUser = new FontUser();
            $fontUser->setUser($user);
            $fontUser->setName($faker->word);
            $fontUser->setFile($faker->word . '.ttf');
            $manager->persist($fontUser);

            // create 20 images
            $image = new Image();
            $image->setName($faker->word);
            $image->setFile($faker->imageUrl);
            $manager->persist($image);

            // create 20 images uploaded
            $imageUploaded = new ImageUploaded();
            $imageUploaded->setUser($user);
            $imageUploaded->setName($faker->word);
            $imageUploaded->setFile($faker->imageUrl);
            $manager->persist($imageUploaded);

            // create 20 logos
            $logo = new Logo();
            $logo->setUser($user);
            $logo->setName($faker->word);
            $logo->setFile($faker->imageUrl);
            $manager->persist($logo);

            // create 20 subscriptions
            $subscription = new Subscription();
            $subscription->setUser($user);
            $subscription->setAddress($address);
            $subscription->setCreditCard($creditCard);
            $subscription->setStartDate($faker->dateTimeBetween('-5 years', 'now'));
            $subscription->setEndDate($faker->dateTimeBetween('now', '+5 years'));
            $subscription->setPaymentType($faker->randomDigit);
            $subscription->setPrice($faker->numberBetween(10, 200));
            $subscription->setInvoice($faker->sentence);
            $manager->persist($subscription);

            // create 20 templates
            $template = new Template();
            $template->setCreationType($creationType);
            $template->setName($faker->word);
            $template->setOrderIndex($i);
            $manager->persist($template);

            // create 20 template creation categories
            $templateFormField = new TemplateFormField();
            $templateFormField->setTemplate($template);
            $templateFormField->setName($faker->word);
            $templateFormField->setLabel($faker->word);
            $templateFormField->setPlaceholder($faker->word);
            $templateFormField->setOptions($faker->sentences);
            $templateFormField->setType($faker->randomDigit);
            $templateFormField->setDefaultValue($faker->word);
            $manager->persist($templateFormField);
        }

        $manager->flush();
    }
}
