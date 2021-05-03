<?php

namespace App\Entity;

use App\Extension\Doctrine\Guidable\GuidableInterface;
use App\Extension\Doctrine\Guidable\GuidableTrait;
use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 */
class Schedule implements GuidableInterface
{
    use GuidableTrait;
    use TimestampableEntity;

//    const TYPE_SINGLE = 10;
    const TYPE_PERIODIC = 20;

    const STATUS_ACTIVE = 0;
    const STATUS_FINISHED = 10;
    const STATUS_STOPPED = 20;
    const STATUS_MISSED = 30;

    public static function getTypes(): array
    {
        return [
//            self::TYPE_SINGLE => 'single',
            self::TYPE_PERIODIC => 'periodic',
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
     * initially days of week when doesn't needed to confirm
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
     * single, periodic, periodic_since
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Tick::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tick;

    /**
     * @ORM\OneToMany(targetEntity=ConfirmationQueue::class, mappedBy="schedule")
     */
    private $confirmationQueues;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $status = self::STATUS_ACTIVE;

    public function __construct()
    {
        $this->confirmationQueues = new ArrayCollection();
    }

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
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

    /**
     * @return Collection|ConfirmationQueue[]
     */
    public function getConfirmationQueues(): Collection
    {
        return $this->confirmationQueues;
    }

    public function addConfirmationQueue(ConfirmationQueue $confirmationQueue): self
    {
        if (!$this->confirmationQueues->contains($confirmationQueue)) {
            $this->confirmationQueues[] = $confirmationQueue;
            $confirmationQueue->setSchedule($this);
        }

        return $this;
    }

    public function removeConfirmationQueue(ConfirmationQueue $confirmationQueue): self
    {
        if ($this->confirmationQueues->removeElement($confirmationQueue)) {
            // set the owning side to null (unless already changed)
            if ($confirmationQueue->getSchedule() === $this) {
                $confirmationQueue->setSchedule(null);
            }
        }

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
}
