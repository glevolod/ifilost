<?php

namespace App\Controller;

use App\Entity\Confirmation;
use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
    /**
     * @Route("/confirm/{guid}", name="confirmation_confirm")
     */
    public function confirm(
        Confirmation $confirmation,
        EntityManagerInterface $entityManager
    ): Response {
        if ($confirmation->getMaxDateTime() < (new \DateTime())->modify('- '.Confirmation::GAP_TIMEOUT.' minutes')) {
            $this->addFlash('warning', 'Сожалеем! Отметка просрочена.');

            return $this->redirectToRoute('index');
        }
        $this->addFlash('success', 'Спасибо! Отметка получена.');
        $confirmation->setStatus(Confirmation::STATUS_CONFIRMED);
        $schedule = $confirmation->getQueue()->getSchedule();
        if ($schedule->getType() == Schedule::TYPE_PERIODIC && $schedule->getStatus() == Schedule::STATUS_ACTIVE) {
            $nextQueue = clone $confirmation->getQueue();
            $this->addFlash(
                'success',
                'Время следующей отметки: <strong>'.$nextQueue->getSendDateTime()->format('Y-m-d H:i').'</strong>'
            );
            $entityManager->persist($nextQueue);
        }
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
