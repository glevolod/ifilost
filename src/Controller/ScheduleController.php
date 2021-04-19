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
        return $this->render('schedule/index.html.twig');
    }

    /**
     * @Route("/schedule_add", name="schedule_add", methods={"GET","POST"})
     */
    public function add(): Response
    {
        $schedule = new Schedule();
        $scheduleForm = $this->createForm(ScheduleFormType::class, $schedule);
        return $this->render('schedule/add.html.twig', ['form' => $scheduleForm->createView()]);
    }
}
