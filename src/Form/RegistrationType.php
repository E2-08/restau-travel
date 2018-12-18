<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AppType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AppType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig(false, 'Votre prenom'))
            ->add('lastName', TextType::class, $this->getConfig(false, 'Votre nom de famille'))
            ->add('email', EmailType::class, $this->getConfig(false, 'Votre adresse email'))
            ->add('avatar', FileType::class, $this->getConfig(false, 'Avator'))
            ->add('hash', PasswordType::class, $this->getConfig(false, 'Choisissez votre mot de passe'))
            ->add('passwordConfirme', PasswordType::class, $this->getConfig(false, "Veuillez confirmer votre mot de passe", [
                'data_class' => null,
                'required' => false
            ]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
