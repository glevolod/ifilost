<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\User;
use App\Event\Event\NewScheduleEvent;
use App\Event\Event\UpdateScheduleEvent;
use App\Form\ScheduleFormType;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    /**
     * @Route("/schedule", name="schedule_index")
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        $schedules = $scheduleRepository->allByUser($this->getUser());

        return $this->render('schedule/index.html.twig', ['schedules' => $schedules]);
    }

    /**
     * @Route("/schedule/new", name="schedule_new", methods={"GET","POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher
    ): Response {
        $schedule = new Schedule();
        $scheduleForm = $this->createForm(ScheduleFormType::class, $schedule);
        $scheduleForm->handleRequest($request);
        if ($scheduleForm->isSubmitted() && $scheduleForm->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $schedule->setUser($user)->setTick($user->getTick());
            $manager->persist($schedule);
            $dispatcher->dispatch(new NewScheduleEvent($schedule), NewScheduleEvent::NAME);
            $manager->flush();

            return $this->redirectToRoute('schedule_index');
        }

        return $this->render('schedule/add.html.twig', ['form' => $scheduleForm->createView()]);
    }

    /**
     * @Route("/schedule/update/{guid}", name="schedule_update", methods={"GET","POST"})
     */
    public function update(
        Request $request,
        Schedule $schedule,
        EntityManagerInterface $manager,
        EventDispatcherInterface $dispatcher
    ): Response {
        $scheduleForm = $this->createForm(ScheduleFormType::class, $schedule);
        $scheduleForm->handleRequest($request);
        if ($scheduleForm->isSubmitted() && $scheduleForm->isValid()) {
            /** @var User $user */
            $dispatcher->dispatch(new UpdateScheduleEvent($schedule), UpdateScheduleEvent::NAME);
            $manager->flush();

            return $this->redirectToRoute('schedule_index');
        }

        return $this->render('schedule/add.html.twig', ['form' => $scheduleForm->createView()]);
    }
}
