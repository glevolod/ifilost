<?php


namespace App\Extension\Twig;


use App\Entity\User;
use App\Repository\UserRepository;
use Twig\Extension\RuntimeExtensionInterface;

class RuntimeScheduleMenu implements RuntimeExtensionInterface
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function isEnabled(User $user): bool
    {
        return $this->userRepository->isScheduleEnabled($user);
    }

}