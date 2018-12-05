<?php

namespace App\Form;

use App\Form\AppType;
use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PropertySearchType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
            ->add('language',TextType::class,$this->getConfig(false,'Langue',array('required' => false,'attr'=>['class'=>'text-center'])))
            ->add('city',TextType::class,$this->getConfig(false,'ville',array(
                    'required' => false,'attr'=>['class'=>'text-center'])
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'Get',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix(){
        return '';
    }
}