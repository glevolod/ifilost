<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function index(): Response
    {
        $schedule = new Schedule();
        $scheduleForm = $this->createForm(ScheduleFormType::class, $schedule);

        return $this->render('schedule/index.html.twig', ['scheduleForm' => $scheduleForm->createView()]);
    }
}
