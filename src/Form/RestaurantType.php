<?php

namespace App\Form;

use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RestaurantType extends AbstractType
{
    /**
     * This function allows you to recover from a field
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfig($label,$placeholder){
        return[
            'label' => $label,
            'attr' => ['placeholder' => $placeholder]
        ];

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,$this->getConfig('Nom','Nom'))
            ->add('adress',TextType::class,$this->getConfig('Adresse','Adresse'))
            ->add('phone',TextType::class,$this->getConfig('Phone','Phone'))
            ->add('email',TextType::class,$this->getConfig('Email','Email'))
            ->add('city',TextType::class,$this->getConfig('Ville','Ville'))
            ->add('country',TextType::class,$this->getConfig('Adresse','Pays'))
            ->add('coverimages');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
