<?php

namespace App\Entity;

use App\Extension\Doctrine\Guidable\GuidableInterface;
use App\Extension\Doctrine\Guidable\GuidableTrait;
use App\Repository\ConfirmationQueueRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ConfirmationQueueRepository::class)
 */
class ConfirmationQueue implements GuidableInterface
{
    use GuidableTrait;
    use TimestampableEntity;

    const STATUS_NEW = 0;
    const STATUS_SENT = 1;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tick::class, inversedBy="confirmationQueues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tick;

    /**
     * @ORM\ManyToOne(targetEntity=Schedule::class, inversedBy="confirmationQueues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $schedule;

    /**
     * @ORM\Column(type="smallint", options={"default": 0})
     */
    private $status = self::STATUS_NEW;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendDateTime;

    public function __clone(){
        $this->id = null;
        $this->status = self::STATUS_NEW;
        $this->sendDateTime = $this->sendDateTime->modify("+ {$this->schedule->getFrequency()} hours");
        $this->createdAt = null;
        $this->updatedAt = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTick(): ?Tick
    {
        return $this->tick;
    }

    public function setTick(?Tick $tick): self
    {
        $this->tick = $tick;

        return $this;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(?Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSendDateTime(): ?\DateTime
    {
        return $this->sendDateTime;
    }

    public function setSendDateTime(\DateTimeInterface $sendDateTime): self
    {
        $this->sendDateTime = $sendDateTime;

        return $this;
    }
}
