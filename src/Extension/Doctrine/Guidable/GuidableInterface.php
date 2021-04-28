<?php


namespace App\Extension\Doctrine\Guidable;

/**
 * Interface GuidableInterface
 * @package App\Extension\Doctrine\Guidable
 */
interface GuidableInterface
{
    /**
     * @return string|null
     */
    public function getGuid(): ?string ;
}