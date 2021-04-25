<?php


namespace App\Event\Event;


use App\Entity\Schedule;
use Symfony\Contracts\EventDispatcher\Event;

class NewScheduleEvent extends Event
{
    public const NAME = 'schedule.new';

    protected $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }
}