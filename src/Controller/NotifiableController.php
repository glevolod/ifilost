<?php

namespace App\Controller;

use App\Entity\Notifiable;
use App\Entity\Schedule;
use App\Entity\User;
use App\Form\NotifiableType;
use App\Repository\NotifiableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notifiable")
 */
class NotifiableController extends AbstractController
{
    /**
     * @Route("/", name="notifiable_index", methods={"GET"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('notifiable/index.html.twig', [
            'notifiables' => $user->getNotifiables(),
        ]);
    }

    /**
     * @Route("/new", name="notifiable_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $notifiable = new Notifiable();
        $form = $this->createForm(NotifiableType::class, $notifiable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $notifiable->setUser($this->getUser());
            $entityManager->persist($notifiable);
            $entityManager->flush();

            return $this->redirectToRoute('notifiable_index');
        }

        return $this->render('notifiable/new.html.twig', [
            'notifiable' => $notifiable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notifiable_show", methods={"GET"})
     */
    public function show(Notifiable $notifiable): Response
    {
        return $this->render('notifiable/show.html.twig', [
            'notifiable' => $notifiable,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="notifiable_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Notifiable $notifiable): Response
    {
        $form = $this->createForm(NotifiableType::class, $notifiable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notifiable_index');
        }

        return $this->render('notifiable/edit.html.twig', [
            'notifiable' => $notifiable,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notifiable_delete", methods={"POST"})
     */
    public function delete(Request $request, Notifiable $notifiable): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notifiable->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($notifiable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('notifiable_index');
    }
}
