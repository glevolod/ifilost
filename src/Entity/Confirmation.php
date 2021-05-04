<?php

namespace App\Entity;

use App\Extension\Doctrine\Guidable\GuidableInterface;
use App\Extension\Doctrine\Guidable\GuidableTrait;
use App\Repository\ConfirmationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ConfirmationRepository::class)
 */
class Confirmation implements GuidableInterface
{
    use GuidableTrait;
    use TimestampableEntity;

    const STATUS_WAITING = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_MISSED = 2;
    const STATUS_ATTEMPTS_EXCEEDED = 3;
    const STATUS_FAIL_CONFIRMED = 4;

    const TYPE_FIRST_CONFIRMATION = 0;
    const TYPE_REMINDER_CONFIRMATION = 1;

    const MAX_ATTEMPTS = 3;

    const GAP_TIMEOUT = 2; //minutes

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $attempts = 0;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $status = self::STATUS_WAITING;

    /**
     * @ORM\Column(type="smallint", options={"default":0})
     */
    private $type = self::TYPE_FIRST_CONFIRMATION;

    /**
     * @ORM\ManyToOne(targetEntity=ConfirmationQueue::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $queue;

    /**
     * @ORM\Column(type="datetime")
     */
    private $maxDateTime;

    /**
     * @ORM\OneToOne(targetEntity=Notification::class, mappedBy="confirmation", cascade={"persist", "remove"})
     */
    private $notification;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function increaseAttempts(){
        $this->attempts++;
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

    public function getQueue(): ?ConfirmationQueue
    {
        return $this->queue;
    }

    public function setQueue(?ConfirmationQueue $queue): self
    {
        $this->queue = $queue;

        return $this;
    }

    public function getMaxDateTime(): ?\DateTime
    {
        return $this->maxDateTime;
    }

    public function setMaxDateTime(\DateTime $maxDateTime): self
    {
        $this->maxDateTime = $maxDateTime;

        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(Notification $notification): self
    {
        // set the owning side of the relation if necessary
        if ($notification->getConfirmation() !== $this) {
            $notification->setConfirmation($this);
        }

        $this->notification = $notification;

        return $this;
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
}
