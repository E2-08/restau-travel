<?php

namespace App\Form;


use App\Form\AppType;
use App\Entity\Booking;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class BookingType extends AppType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Symfony\Component\Form\Extension\Core\Type\DateTimeType
        $builder->add('tempDate', TextType::class,array(
                   
                    'label'=> false
                 ))
                 ->add('tempTime', TextType::class,array(
                    'attr' => array(
                        'class' => 'btn btn-success ml-2',
                    ),
                    'label'=> false
                 ))
                 ->add('tempPeople', TextType::class,array(
                    'attr' => array(
                        'class' => 'btn btn-success ml-2',
                    ),
                    'label'=> false
                 ));
               
             
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        

        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
