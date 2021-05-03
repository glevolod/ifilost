<?php

namespace App\Entity;

use App\Extension\Doctrine\Guidable\GuidableInterface;
use App\Extension\Doctrine\Guidable\GuidableTrait;
use App\Repository\NotifiableRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=NotifiableRepository::class)
 */
class Notifiable implements GuidableInterface
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifiables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $emailConfirmedAt;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    public function __toString(): string
    {
        return $this->email;
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

    public function getEmail(): ?string
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
