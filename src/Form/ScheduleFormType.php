<?php

namespace App\Form;

use App\Entity\Schedule;
use App\Extension\Form\DatePickerType;
use App\Extension\Form\TimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleFormType extends AbstractType implements DataTransformerInterface
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

        $builder->get('exceptions')->addModelTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Schedule::class,
            ]
        );
    }

    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return implode(',', $value);
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }
        try {
            return explode(',', $value);
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

    }

}
