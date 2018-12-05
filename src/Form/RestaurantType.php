<?php

namespace App\Form;

use App\Form\AppType;
use App\Entity\Restaurant;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RestaurantType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,$this->getConfig('Name','Restaurant name'))
            ->add('adress',TextType::class,$this->getConfig('Adress','Adress'))
            ->add('phone',TextType::class,$this->getConfig('Phone','Phone'))
            ->add('city',TextType::class,$this->getConfig('City','City'))
            ->add('coverimages');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
