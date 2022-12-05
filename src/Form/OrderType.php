<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, [
                "class"=>Address::class,
                "required"=>true,
                "multiple"=>false,
                "choices"=>$user->getAddresses(),
                "expanded"=>true,
                "label"=>false
            ])
            ->add('carriers', EntityType::class, [
                "class"=>Carrier::class,
                "required"=>true,
                "multiple"=>false,
                "expanded"=>true,
                "label"=>"Choose your carrier"
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Confirm Order",
                "attr" => [
                    "class" => "btn btn-block btn-success"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
