<?php

namespace App\Controller;

use App\Entity\Tick;
use App\Entity\User;
use App\Form\TickType;
use App\Repository\TickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tick")
 */
class TickController extends AbstractController
{
    /**
     * @Route("", name="tick_index", methods={"GET"})
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('tick/index.html.twig', [
            'tick' => $user->getTick(),
        ]);
    }

    /**
     * @Route("/new", name="tick_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tick = new Tick();
        $form = $this->createForm(TickType::class, $tick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var User $user */
            $user = $this->getUser();
            $tick->setUser($user)->setEmail($user->getEmail());
            $entityManager->persist($tick);
            $entityManager->flush();

            return $this->redirectToRoute('tick_index');
        }

        return $this->render('tick/new.html.twig', [
            'tick' => $tick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tick_show", methods={"GET"})
     */
    public function show(Tick $tick): Response
    {
        return $this->render('tick/show.html.twig', [
            'tick' => $tick,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tick $tick): Response
    {
        $form = $this->createForm(TickType::class, $tick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tick_index');
        }

        return $this->render('tick/edit.html.twig', [
            'tick' => $tick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tick_delete", methods={"POST"})
     */
    public function delete(Request $request, Tick $tick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tick_index');
    }
}
