<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                "disabled"=>true
            ])
            ->add('lastname', TextType::class, [
                "disabled"=>true
            ])
            ->add('email', EmailType::class, [
                "disabled"=>true
            ])
            //->add('roles')
            ->add('old_password',PasswordType::class,[
                "label" => "Current password",
                "mapped"=>false,
                "attr"=>[
                    "placeholder"=>"You current password"
                ]
            ])
            ->add('new_password', RepeatedType::class,  ['type'=>PasswordType::class,'mapped'=>false, 'required'=>true, 'invalid_message'=>'Passwords mismatch',
            'first_options' => ['label'=>'New password'],'second_options'=>['label'=> 'Confirm new password']])
            ->add('submit', SubmitType::class, ['label' => 'Save changes'])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
