<?php

namespace App\Event\Subscriber;

use App\Entity\ConfirmationQueue;
use App\Event\Event\NewScheduleEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ScheduleSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $manager;

    /**
     * ScheduleSubscriber constructor.
     * @param $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    public function onScheduleNew(NewScheduleEvent $event)
    {
        $schedule = $event->getSchedule();
        $user = $schedule->getUser();
        $queue = new ConfirmationQueue();
        $sendDateTime = \DateTime::createFromFormat(
            'Y-m-d H:i',
            $schedule->getDate()->format('Y-m-d').' '.$schedule->getTime()->format('H:i')
        );
        $queue
            ->setSendDateTime($sendDateTime)
            ->setTick($schedule->getTick())
            ->setSchedule($schedule);
        $this->manager->persist($queue);
    }

    public static function getSubscribedEvents()
    {
        return [
            NewScheduleEvent::NAME => 'onScheduleNew',
        ];
    }
}
