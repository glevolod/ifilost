<?php


namespace App\Extension\Doctrine\Guidable\EventListener;


use App\Extension\Doctrine\Guidable\GuidableInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;

/**
 * Class GuidSetter
 * @package App\Extension\Doctrine\Guidable\EventListener
 */
class GuidSetter
{
    /**
     * @param LifecycleEventArgs $args
     * @throws \ReflectionException
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if (!$object instanceof GuidableInterface) {
            return;
        }
        $reflectionObject = new \ReflectionObject($object);
        $reflectionUuidProperty = $reflectionObject->getProperty('guid');
        $reflectionUuidProperty->setAccessible(true);
        $reflectionUuidProperty->setValue($object, uuid_create());
        $reflectionUuidProperty->setAccessible(false);
    }
}