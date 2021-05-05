<?php

namespace App\Event\Subscriber;

use App\Entity\ConfirmationQueue;
use App\Entity\Schedule;
use App\Event\Event\NewScheduleEvent;
use App\Event\Event\UpdateScheduleEvent;
use App\Repository\ConfirmationQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ScheduleSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $manager;
    private ConfirmationQueueRepository $confirmationQueueRepository;

    /**
     * ScheduleSubscriber constructor.
     * @param  EntityManagerInterface  $manager
     * @param  ConfirmationQueueRepository  $confirmationQueueRepository
     */
    public function __construct(
        EntityManagerInterface $manager,
        ConfirmationQueueRepository $confirmationQueueRepository
    ) {
        $this->manager = $manager;
        $this->confirmationQueueRepository = $confirmationQueueRepository;
    }


    public function onScheduleNew(NewScheduleEvent $event)
    {
        $schedule = $event->getSchedule();
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

    public function onScheduleUpdate(UpdateScheduleEvent $event)
    {
        $schedule = $event->getSchedule();
        $confirmationQueues = $this->confirmationQueueRepository->getByScheduleId(
            $schedule->getId(),
            ConfirmationQueue::STATUS_NEW
        );
        foreach ($confirmationQueues as $confirmationQueue) {
            //todo: mb not remove but set a status as SCHEDULE_UPDATED??
            $this->manager->remove($confirmationQueue);
        }

        $schedule->setStatus(Schedule::STATUS_ACTIVE);
        $queue = new ConfirmationQueue();
        $this->manager->persist($queue);
        $sendDateTime = \DateTime::createFromFormat(
            'Y-m-d H:i',
            $schedule->getDate()->format('Y-m-d').' '.$schedule->getTime()->format('H:i')
        );
        $queue
            ->setSendDateTime($sendDateTime)
            ->setTick($schedule->getTick())
            ->setSchedule($schedule);
    }

    public static function getSubscribedEvents()
    {
        return [
            NewScheduleEvent::NAME => 'onScheduleNew',
            UpdateScheduleEvent::NAME => 'onScheduleUpdate',
        ];
    }
}
