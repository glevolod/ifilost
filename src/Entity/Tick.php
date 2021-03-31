<?php

namespace App\Entity;

use App\Repository\TickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TickRepository::class)
 */
class Tick
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $failSign;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sign;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prompt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $emailConfirmedAt;

    /**
     * @ORM\OneToMany(targetEntity=TickQueue::class, mappedBy="tick", orphanRemoval=true)
     */
    private $tickQueues;

    public function __construct()
    {
        $this->tickQueues = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFailSign():? string
    {
        return $this->failSign;
    }

    public function setFailSign(?string $failSign): self
    {
        $this->failSign = $failSign;

        return $this;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }

    public function getPrompt():? string
    {
        return $this->prompt;
    }

    public function setPrompt(?string $prompt): self
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailConfirmedAt():? \DateTimeInterface
    {
        return $this->emailConfirmedAt;
    }

    public function setEmailConfirmedAt(?\DateTimeInterface $emailConfirmedAt): self
    {
        $this->emailConfirmedAt = $emailConfirmedAt;

        return $this;
    }

    /**
     * @return Collection|TickQueue[]
     */
    public function getTickQueues(): Collection
    {
        return $this->tickQueues;
    }

    public function addTickQueue(TickQueue $tickQueue): self
    {
        if (!$this->tickQueues->contains($tickQueue)) {
            $this->tickQueues[] = $tickQueue;
            $tickQueue->setTick($this);
        }

        return $this;
    }

    public function removeTickQueue(TickQueue $tickQueue): self
    {
        if ($this->tickQueues->removeElement($tickQueue)) {
            // set the owning side to null (unless already changed)
            if ($tickQueue->getTick() === $this) {
                $tickQueue->setTick(null);
            }
        }

        return $this;
    }

}
