<?php

namespace App\Form;

use App\Form\AppType;
use App\Entity\Restaurant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RestaurantProfileType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, $this->getConfig("Nom", "Nom de l'établissement"))
            ->add('adress', TextType::class, $this->getConfig("Adresse", "Adresse de l'établissement"))
            ->add('phone', TextType::class, $this->getConfig("Phone", "Téléphone de l'établissement"))
            ->add('email', TextType::class, $this->getConfig("Email", "Email de l'atablissement"))
            ->add('city', TextType::class, $this->getConfig("Ville", "ville"))
            ->add('country', TextType::class, $this->getConfig("Pays", "Pays"))
            ->add('timesolt', TextType::class, $this->getConfig("Plage d'ouverture", "Plage d'ouverture"))
            ->add('bookinglimit', NumberType::class, $this->getConfig("Nombre limite", "limite réservation"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}
