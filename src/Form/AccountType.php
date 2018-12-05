<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AppType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AccountType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfig('Prenom','Votre prenom'))
            ->add('lastName',TextType::class,$this->getConfig('Nom','Votre nom de famille'))
            ->add('email',EmailType::class,$this->getConfig('Email','Votre adresse email'))
            ->add('flagRole', CheckboxType::class, array(
                'label'    => 'Cochez cette case soi vous être restaurateur',
                'required' => false
            ));
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
