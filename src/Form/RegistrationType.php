<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
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
            ->add('firstName',TextType::class,$this->getConfig('Prenom','Votre prenom'))
            ->add('lastName',TextType::class,$this->getConfig('Nom','Votre nom de famille'))
            ->add('avatar',TextType::class,$this->getConfig('Nom','Nom'))
            ->add('hash',PasswordType::class,$this->getConfig('Mot de passe','Choisissez votre mot de passe'))
            ->add('slug',UrlType::class,$this->getConfig('Photo de profil','Url de votre avatar'))
            ->add('email',EmailType::class,$this->getConfig('Email','Votre adresse email'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
