<?php


namespace App\Form;

use App\Entity\Logo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\UX\Dropzone\Form\DropzoneType;

class CharterColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color1', \Symfony\Component\Form\Extension\Core\Type\ColorType::class , [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'random-color'
                ]
            ])
            ->add('color2', \Symfony\Component\Form\Extension\Core\Type\ColorType::class , [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'random-color'
                ]
            ])
            ->add('color3', \Symfony\Component\Form\Extension\Core\Type\ColorType::class , [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'random-color'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Suivant'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true
        ]);
    }

    public function convertArrayToHex($arr)
    {
        $chars = array_map("chr", $arr);
        $bin = join($chars);
        $hex = bin2hex($bin);
        $hex = '#' . $hex;

        return $hex;
    }
}