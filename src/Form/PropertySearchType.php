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

        $builder->add('language', ChoiceType::class, $this->getConfig('les Langues', 'Langue', array(
            'required' => false,
            'attr' => ['class' => 'text-center', 'label' => 'les Langues'], 'choices' => array(
                'Toutes les langues'=>'',
                'Français' => 'Français',
                'Anglais' => 'Anglais',
                'Portugais' => 'Portugais',
                'Nerlandais' => 'Nerlandais',
                'Italien' => 'Italien',
                'Allemand' => 'Allemand',
                'Spagnol' => 'Spagnol'
            )
        )));
           // ->add('city', TextType::class, $this->getConfig(false, 'Ville', ['required' => false]));

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
