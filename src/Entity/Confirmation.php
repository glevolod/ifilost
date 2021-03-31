<?php

namespace App\Entity;

use App\Repository\ConfirmationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ConfirmationRepository::class)
 */
class Confirmation
{
    use TimestampableEntity;

    const STATUS_NEW = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_MISSED = 2;
    const STATUS_ATTEMPTS_EXCEEDED = 3;
    const STATUS_FAIL_CONFIRMED = 4;

    const TYPE_FIRST_CONFIRMATION = 0;
    const TYPE_REMINDER_CONFIRMATION = 1;

    const MAX_ATTEMPTS = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Schedule::class, inversedBy="confirmation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $schedule;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $attempts = 0;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $status = self::STATUS_NEW;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $type = self::TYPE_FIRST_CONFIRMATION;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function setSchedule(Schedule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
