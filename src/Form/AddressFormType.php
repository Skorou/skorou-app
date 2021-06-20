<?php


namespace App\Form;


use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressFormType extends AbstractType
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address1', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Adresse postale'
                )
            ])
            ->add('address2', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Complément d\'adresse (étage, apt...)'
                )
            ])
            ->add('zipcode', IntegerType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Code postal'
                )
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Ville'
                )
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Pays'
                )
            ])
            ->add('phoneNumber', IntegerType::class, [
                'required' => false,
                'label'    => false,
                'attr'     => array(
                    'placeholder' => 'Numéro de téléphone'
                )
            ])
            ->add('faxNumber', IntegerType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Numéro de fax'
                )
            ])
            ->add('firstname', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Prénom du gérant'
                )
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Nom du gérant'
                )
            ])
            ->add('website', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Site internet'
                )
            ])
            ->add('companyStatus', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Statut de l\'entreprise'
                )
            ])
            ->add('capital', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Capital'
                )
            ])
            ->add('apeCode', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Code APE'
                )
            ])
            ->add('siret', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'SIRET'
                )
            ])
            ->add('siren', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'SIREN'
                )
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Continuer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}