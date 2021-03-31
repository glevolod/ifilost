<?php

namespace App\Entity;

use App\Repository\TickQueueRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TickQueueRepository::class)
 */
class TickQueue
{
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
     * @ORM\ManyToOne(targetEntity=Tick::class, inversedBy="tickQueues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tick;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDateTime;

    /**
     * @ORM\Column(type="smallint", options={"default": 0})
     */
    private $status = self::STATUS_NEW;

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

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

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
