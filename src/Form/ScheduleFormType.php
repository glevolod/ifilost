<?php

namespace App\Form;

use App\Entity\Schedule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', TextType::class)
            ->add('time', TextType::class)
            ->add('frequency', NumberType::class)
            ->add('exceptions')
            ->add('timeout', NumberType::class)
            ->add('reminderTimeout', NumberType::class)
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => array_flip(Schedule::getTypes()),
                    'expanded' => true,
                    'multiple' => false,
                    'data' => Schedule::TYPE_PERIODIC,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Schedule::class,
            ]
        );
    }
}
