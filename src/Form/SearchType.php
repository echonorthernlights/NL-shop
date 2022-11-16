<?php

namespace App\Form;

use App\Entity\Category;
use App\UClass\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('searchTerm', TextType::class, [
            'label'=>false,
            'required'=> false,
            'attr' => [
                'placeholder' => 'Search ....',
                'class' => 'form-control-sm'
            ]

            ])->add('searchCategories', EntityType::class, [
                'label'=>'Category',
                'required'=> false,
                'class'=>Category::class,
                'multiple'=>true,
                'expanded'=>true,
                    
            ])->add('submit', SubmitType::class, [
                'label'=>'Filter',
                'attr'=>[
                    'class' => 'btn-block btn-info'
                ]
                    
            ]);
    }
  
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'crsf_protection' => false,
            'method' => 'GET'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}


?>