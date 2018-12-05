<?php

namespace App\Form;

use App\Form\AppType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class,$this->getConfig("Ancien mot de passe","Donnez votre mot de passe actuel ...!"))
            ->add('newPassword', PasswordType::class,$this->getConfig("Nouveau mot de passe","Donnez votre mot de passe actuel ..."))
            ->add('confirmPassword', PasswordType::class,$this->getConfig("Confirmation du mot de passe","Conformer votre mot de passe ..."));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
