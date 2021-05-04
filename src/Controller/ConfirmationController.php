<?php

namespace App\Controller;

use App\Entity\Confirmation;
use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends AbstractController
{
    /**
     * @Route("/confirm/{guid}", name="confirmation_confirm", methods={"GET", "POST"})
     */
    public function confirmForm(
        Request $request,
        Confirmation $confirmation,
        EntityManagerInterface $entityManager
    ): Response {
        if ($confirmation->getStatus() != Confirmation::STATUS_WAITING) {
            throw $this->createNotFoundException();
        }
        $form = $this->createFormBuilder()
            ->add('pin', TextType::class, ['required' => false])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($confirmation->getMaxDateTime() < (new \DateTime())->modify(
                    '- '.Confirmation::GAP_TIMEOUT.' minutes'
                )) {
                $this->addFlash('warning', 'Сожалеем! Отметка просрочена.');

                return $this->redirectToRoute('index');
            }

            $tick = $confirmation->getQueue()->getTick();
            $schedule = $confirmation->getQueue()->getSchedule();
            $pin = $form->getData()['pin'];
            $redirectUrl = $this->generateUrl('index');
            //todo: use pin hashing with a crypto algorithm
            if (!empty($tick->getFailSign()) && $pin == $tick->getFailSign()) {
                $confirmation->setStatus(Confirmation::STATUS_FAIL_CONFIRMED);
            } elseif (!empty($tick->getSign()) && $pin == $tick->getSign()) {
                $confirmation->setStatus(Confirmation::STATUS_CONFIRMED);
            } elseif (!empty($tick->getSign()) && $pin != $tick->getSign()) {
                $confirmation->increaseAttempts();
            } elseif (empty($tick->getSign()) && !empty($pin)) {// empty sign not need a pin
                $confirmation->increaseAttempts();
            } else {// empty($tick->getSign()) && empty($pin)
                $confirmation->setStatus(Confirmation::STATUS_CONFIRMED);
            }

            if ($confirmation->getStatus() == Confirmation::STATUS_CONFIRMED) {
                $this->addFlash('success', 'Спасибо! Отметка получена.');
                if (//start new ConfirmationQueue item if periodic
                    $schedule->getType() == Schedule::TYPE_PERIODIC
                    && $schedule->getStatus() == Schedule::STATUS_ACTIVE
                ) {
                    $nextQueue = clone $confirmation->getQueue();
                    $this->addFlash(
                        'success',
                        'Время следующей отметки: <strong>'.$nextQueue->getSendDateTime()->format(
                            'Y-m-d H:i'
                        ).'</strong>'
                    );
                    $entityManager->persist($nextQueue);
                }
            } elseif ($confirmation->getStatus() == Confirmation::STATUS_FAIL_CONFIRMED) {
                $this->addFlash('success', 'Спасибо!');
            } elseif ($confirmation->getAttempts() >= Confirmation::MAX_ATTEMPTS) {
                $confirmation->setStatus(Confirmation::STATUS_ATTEMPTS_EXCEEDED);
                $this->addFlash('warning', 'Сожалеем! Количество попыток превышено.');
            } else {
                $this->addFlash('warning', 'Попробуйте еще раз.');
                $redirectUrl = $this->generateUrl('confirmation_confirm', ['guid' => $confirmation->getGuid()]);
            }

            $entityManager->flush();

            return $this->redirect($redirectUrl);
        }

        return $this->render('confirmation/confirm.html.twig', ['form' => $form->createView()]);
    }
}
