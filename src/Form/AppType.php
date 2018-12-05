<?php 
namespace App\Form;
use Symfony\Component\Form\AbstractType;

class AppType extends AbstractType {

     /**
     * This function allows you to recover from a field
     *
     * @param string $label
     * @param string $placeholder
     * @return arrayusername
     */
    protected function getConfig($label,$placeholder, $options = []){
        
        return array_merge_recursive([
            'label' => $label,
            'attr' => ['placeholder' => $placeholder]
        ],$options);

    }
}