<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleFormType;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        $schedules = $scheduleRepository->findByUser($this->getUser());
        return $this->render('schedule/index.html.twig', ['schedules' => $schedules]);
    }

    /**
     * @Route("/schedule_add", name="schedule_add", methods={"GET","POST"})
     */
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $schedule = new Schedule();
        $scheduleForm = $this->createForm(ScheduleFormType::class, $schedule);
        $scheduleForm->handleRequest($request);
        if ($scheduleForm->isSubmitted() && $scheduleForm->isValid()) {
            $schedule->setUser($this->getUser());
            $manager->persist($schedule);
            $manager->flush();

            return $this->redirectToRoute('schedule');
        }

        return $this->render('schedule/add.html.twig', ['form' => $scheduleForm->createView()]);
    }
}
