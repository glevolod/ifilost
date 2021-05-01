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
            //todo: check type and send new reminder confirmation if it wasn't reminder
            $confirmation->setStatus(Confirmation::STATUS_MISSED);
            $entityManager->flush();
            //todo: send notifications for notifiables
            return $this->redirectToRoute('index');
        }
        $this->addFlash('success', 'Спасибо! Отметка получена.');
        $confirmation->setStatus(Confirmation::STATUS_CONFIRMED);
        if ($confirmation->getQueue()->getSchedule()->getType() == Schedule::TYPE_PERIODIC) {
            $nextQueue = clone $confirmation->getQueue();
            $entityManager->persist($nextQueue);
        }
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
