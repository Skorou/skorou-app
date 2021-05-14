<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'required' => true,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Adresse'
                )
            ])
            ->add('address2', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Complément d\'adresse (étage, apt...)'
                )
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Complément d\'adresse (étage, apt...)'
                )
            ])
            ->add('zipcode', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Complément d\'adresse (étage, apt...)'
                )
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label'    => false,
                'attr' => array(
                    'placeholder' => 'Complément d\'adresse (étage, apt...)'
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
            'data_class' => User::class,
        ]);
    }
}