<?php

namespace App\Entity;

use App\Extension\Doctrine\Guidable\GuidableInterface;
use App\Extension\Doctrine\Guidable\GuidableTrait;
use App\Repository\TickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TickRepository::class)
 */
class Tick implements GuidableInterface
{
    use GuidableTrait;
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
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="tick", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ConfirmationQueue::class, mappedBy="tick")
     */
    private $confirmationQueues;

    public function __construct()
    {
        $this->confirmationQueues = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFailSign(): ?string
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

    public function getPrompt(): ?string
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

    public function getEmailConfirmedAt(): ?\DateTimeInterface
    {
        return $this->emailConfirmedAt;
    }

    public function setEmailConfirmedAt(?\DateTimeInterface $emailConfirmedAt): self
    {
        $this->emailConfirmedAt = $emailConfirmedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
            $confirmationQueue->setTick($this);
        }

        return $this;
    }

    public function removeConfirmationQueue(ConfirmationQueue $confirmationQueue): self
    {
        if ($this->confirmationQueues->removeElement($confirmationQueue)) {
            // set the owning side to null (unless already changed)
            if ($confirmationQueue->getTick() === $this) {
                $confirmationQueue->setTick(null);
            }
        }

        return $this;
    }

}
