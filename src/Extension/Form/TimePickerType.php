<?php


namespace App\Extension\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TimePickerType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof \DateTimeInterface) {
            throw new TransformationFailedException('Expected a \DateTimeInterface.');
        }

        return $value->format('H:i');
    }

    public function reverseTransform($value)
    {
        try {
            if (!preg_match("/(\d{2}):(\d{2})$/", $value, $matches)) {
                throw new TransformationFailedException('Invalid date format hh:ss');
            }

            if (0 > (int)$matches[1] || (int)$matches[1] > 23) {
                throw new TransformationFailedException('Wrong hours value');
            }
            if (0 > (int)$matches[2] || (int)$matches[2] > 59) {
                throw new TransformationFailedException('Wrong minutes value');
            }
            $date = new \DateTime(sprintf('0000-00-00 %s:%s:0', $matches[1], $matches[2]));
        } catch (\Exception $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

        return $date;
    }

    public function getParent()
    {
        return TextType::class;
    }
}