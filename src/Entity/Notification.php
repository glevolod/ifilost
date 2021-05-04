<?php

namespace App\Entity;

use App\Extension\Doctrine\Guidable\GuidableInterface;
use App\Extension\Doctrine\Guidable\GuidableTrait;
use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification implements GuidableInterface
{
    use GuidableTrait;
    use TimestampableEntity;

    //mb not needed TYPE_*
    const TYPE_CUSTOM = 0;
    const TYPE_CONFIRMATION_FAILED = 10;

    const STATUS_NEED_SEND = 0;
    const STATUS_SENT = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity=Confirmation::class, inversedBy="notification", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $confirmation;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status = self::STATUS_NEED_SEND;

    /**
     * @ORM\ManyToMany(targetEntity=Notifiable::class)
     */
    private $notifiables;

    public function __construct()
    {
        $this->notifiables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setTypeConfirmationFailed(): self
    {
        $this->type = self::TYPE_CONFIRMATION_FAILED;

        return $this;
    }

    public function getConfirmation(): ?Confirmation
    {
        return $this->confirmation;
    }

    public function setConfirmation(Confirmation $confirmation): self
    {
        $this->confirmation = $confirmation;

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

    public function setStatusSent(): self
    {
        $this->status = self::STATUS_SENT;

        return $this;
    }

    /**
     * @return Collection|Notifiable[]
     */
    public function getNotifiables(): Collection
    {
        return $this->notifiables;
    }

    public function addNotifiable(Notifiable $notifiable): self
    {
        if (!$this->notifiables->contains($notifiable)) {
            $this->notifiables[] = $notifiable;
        }

        return $this;
    }

    /**
     * @param  Collection<Notifiable>  $notifiables
     * @return $this
     */
    public function addNotifiables(Collection $notifiables): self
    {
        foreach ($notifiables as $notifiable) {
            $this->addNotifiable($notifiable);
        }

        return $this;
    }

    public function removeNotifiable(Notifiable $notifiable): self
    {
        $this->notifiables->removeElement($notifiable);

        return $this;
    }
}
