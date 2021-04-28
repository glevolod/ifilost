<?php


namespace App\Extension\Doctrine\Guidable;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait GuidableTrait
 * @package App\Extension\Doctrine\Guidable
 */
trait GuidableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="guid", unique=true)
     */
    private $guid;

    /**
     * @return string|null
     */
    public function getGuid(): ?string
    {
        return $this->guid;
    }
}