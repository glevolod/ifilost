<?php

namespace App\Form;

use App\Entity\Schedule;
use App\Extension\Form\DatePickerType;
use App\Extension\Form\TimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DatePickerType::class)
            ->add('time', TimePickerType::class)
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
            )
            ->add('save', SubmitType::class);
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
