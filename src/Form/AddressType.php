<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Your address name'])
            ->add('firstName', TextType::class, ['label' => 'Your Firstname'])
            ->add('lastName', TextType::class, ['label' => 'Your Lastname'])
            ->add('company', TextType::class, ['label' => 'Your company name'])
            ->add('address', TextType::class, ['label' => 'Your address'])
            ->add('zipCode', TextType::class, ['label' => 'Your ZIP code'])
            ->add('city', TextType::class, ['label' => 'City'])
            ->add('country', CountryType::class, ['label' => 'Country', 'attr'=>[
                'class' => 'form-control'
            ]])
            ->add('phone', TelType::class, ['label' => 'Your phone'])
            ->add('submit', SubmitType::class, ['label' => 'Submit', 'attr' => [
                'class' => 'btn btn-info btn-block'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
