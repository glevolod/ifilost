<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 */
class Schedule
{
    use TimestampableEntity;

    const TYPE_SINGLE = 10;
    const TYPE_PERIODIC = 20;
    const TYPE_PERIODIC_SINCE = 30;

    public static function getTypes(): array
    {
        return [
            self::TYPE_SINGLE => 'single',
            self::TYPE_PERIODIC => 'periodic',
            self::TYPE_PERIODIC_SINCE => 'periodic since',
        ];
    }

    public static function getTypeName(int $type): string
    {
        return self::getTypes()[$type];
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * hours (or days?)
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $frequency;

    /**
     * initially days of week when does't needed to confirm
     * @ORM\Column(type="json", nullable=true)
     */
    private $exceptions = [];

    /**
     * minutes to wait a confirmation
     * @ORM\Column(type="smallint")
     */
    private $timeout;

    /**
     * minutes
     * if set, then send another confirmation with this timeout
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $reminderTimeout;

    /**
     * @ORM\OneToOne(targetEntity=Confirmation::class, mappedBy="schedule", cascade={"persist", "remove"})
     */
    private $confirmation;

    /**
     * single, periodic, periodic_since
     * @ORM\Column(type="smallint")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(?int $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getExceptions(): ?array
    {
        return $this->exceptions;
    }

    public function setExceptions(?array $exceptions): self
    {
        $this->exceptions = $exceptions;

        return $this;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function getReminderTimeout(): ?int
    {
        return $this->reminderTimeout;
    }

    public function setReminderTimeout(?int $reminderTimeout): self
    {
        $this->reminderTimeout = $reminderTimeout;

        return $this;
    }

    public function getConfirmation(): ?Confirmation
    {
        return $this->confirmation;
    }

    public function setConfirmation(Confirmation $confirmation): self
    {
        // set the owning side of the relation if necessary
        if ($confirmation->getSchedule() !== $this) {
            $confirmation->setSchedule($this);
        }

        $this->confirmation = $confirmation;

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
