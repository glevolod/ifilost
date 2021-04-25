<?php

namespace App\Form;

use App\Entity\Tick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('failSign')
            ->add('sign')
            ->add('prompt')
//            ->add('email')
//            ->add('emailConfirmedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tick::class,
        ]);
    }
}
