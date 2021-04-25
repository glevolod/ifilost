<?php


namespace App\Extension\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DatePickerType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof \DateTimeInterface) {
            throw new TransformationFailedException('Expected a \DateTimeInterface.');
        }

        return $value->format('Y-m-d');
    }

    public function reverseTransform($value)
    {
        try {
            if (!preg_match("/(\d{4})-(\d{2})-(\d{2})$/", $value, $matches)) {
                throw new TransformationFailedException('Invalid date format Y-m-d');
            }
            if (!checkdate($matches[2], $matches[3], $matches[1])) {
                throw new TransformationFailedException('This date is not exist in the Georgian calendar');
            }
            $date = new \DateTime(sprintf('%s-%s-%s 0:0:0', $matches[1], $matches[2], $matches[3]));
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