<?php

namespace App\Form;

use App\Form\AppType;
use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PropertySearchType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('language', ChoiceType::class, $this->getConfig(false, 'Langue', array(
            'required' => false,
            'attr' => ['class' => 'text-center'], 'choices' => array(
                'Français' => 'Français',
                'Anglais' => 'Anglais',
                'Portugais' => 'Portugais',
                'Nerlandais' => 'Nerlandais',
                'Italien' => 'Italien',
                'Allemand' => 'Allemand',
                'Spagnol' => 'Spagnol'
            )
        )));
            // ->add('city', ChoiceType::class, $this->getConfig(false, 'ville', array(
            //     'required' => false, 'attr' => ['class' => 'text-center'],
            //     'choices' => array(
            //         'English' => 'en',
            //         'Spanish' => 'Spanish',
            //         'Bork' => 'muppets',
            //         'Pirate' => 'arr',
            //     ),
            //     'preferred_choices' => array('muppets', 'arr')
            // )));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method' => 'Get',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
