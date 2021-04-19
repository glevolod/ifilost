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
            ->add(
                'date',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'app-datepicker datetimepicker-input',
                        'data-toggle' => 'datetimepicker',
                        'data-target' => '.app-datepicker',
                    ],
                ]
            )
            ->add(
                'time',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'app-timepicker datetimepicker-input',
                        'data-toggle' => 'datetimepicker',
                        'data-target' => '.app-timepicker',
                    ],
                ]
            )
            ->add(
                'frequency',
                NumberType::class,
                [
                    'label' => 'Frequency (h)',
                ]
            )
            ->add(
                'exceptions',
                null,
                [
                    'label' => 'Exceptions (week days)',
                ]
            )
            ->add(
                'timeout',
                NumberType::class,
                [
                    'label' => 'Timeout (m)',
                ]
            )
            ->add(
                'reminderTimeout',
                NumberType::class,
                [
                    'label' => 'Reminder timeout (m)',
                ]
            )
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
