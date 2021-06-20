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
        $randomColorsJson = CharterColorType::getRandomColors();
        $randomColors = json_decode($randomColorsJson);
        $randomColors = (array)$randomColors;

        $moule = gettype($randomColors);

        $bip = CharterColorType::convertArrayToHex($randomColors['result'][0]);
        $bop = 'oui';

        $builder
            ->add('color1', \Symfony\Component\Form\Extension\Core\Type\ColorType::class , [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'value' => CharterColorType::convertArrayToHex($randomColors['result'][0])
                ]
            ])
            ->add('color2', \Symfony\Component\Form\Extension\Core\Type\ColorType::class , [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'value' => CharterColorType::convertArrayToHex($randomColors['result'][1])
                ]
            ])
            ->add('color3', \Symfony\Component\Form\Extension\Core\Type\ColorType::class , [
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'value' => CharterColorType::convertArrayToHex($randomColors['result'][3])
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

    public function getRandomColors()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            "http://colormind.io/api/" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS,     '{"model":"default"}' );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));

        $result = curl_exec ($ch);

        return $result;
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