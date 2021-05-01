<?php

namespace App\Controller;

use App\Entity\Confirmation;
use App\Repository\ConfirmationRepository;
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
        ConfirmationRepository $confirmationRepository,
        EntityManagerInterface $entityManager
    ): Response {
        if ($confirmation->getMaxDateTime()->modify('+ '.Confirmation::GAP_TIMEOUT.' minutes') > new \DateTime()) {
            return $this->redirectToRoute('index');
        }
        $confirmation->setStatus(Confirmation::STATUS_CONFIRMED);
        $nextQueue = clone $confirmation->getQueue();
        $entityManager->persist($nextQueue);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
