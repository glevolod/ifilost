<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\NotifiableRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(TickRepository $tickRepository, NotifiableRepository $notifiableRepository, ScheduleRepository $scheduleRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $tick = $tickRepository->findOneByUser($user);
        $notifiables = $notifiableRepository->allByUser($user);
        $schedules = $scheduleRepository->allByUser($user);
        return $this->render('dashboard/index.html.twig',[
            'tick' => $tick,
            'notifiables' => $notifiables,
            'schedules' => $schedules,
        ]);
    }
}
