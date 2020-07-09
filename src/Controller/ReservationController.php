<?php

namespace App\Controller;

use App\Form\ReservFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\Reservation;

class ReservationController extends AbstractController
{
    /**
     * @Route("/res", name="res")
     */
    public function index()
    { $repo=$this->getDoctrine()->getRepository(Reservation::class);
        $reservations=$repo->findAll();
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'reservations'=>$reservations
        ]);
    }
     /**
     * @Route("/new2", name="add_reservation")
     */
    public function addreservation(Request $request): Response
    {$reservation = new reservation();
        $form = $this->createForm(ReservFormType::class);
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
          $reservation = $form->getData();
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($reservation);
          $entityManager->flush();
  
          return $this->redirectToRoute('res');
        }
        return $this->render("reservation/new2.html.twig", [
            "form_title" => "Ajouter une reservation",
            "form_reservation" => $form->createView(),
        ]);
    }
    /**
 * @Route("/new2/{id}", name="modify")
 */
public function modifyreservation(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $reservation = $entityManager->getRepository(Reservation::class)->find($id);
    $form = $this->createForm(ReservFormType::class, $reservation);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
        return $this->redirectToRoute('res');
    }

    return $this->render("reservation/new2.html.twig", [
        "form_title" => "Modifier reservation",
        "form_reservation" => $form->createView(),
    ]);
}
/**
 * @Route("/delete2/{id}", name="delete_reservation")
 */
public function deletereservation(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $reservation = $entityManager->getRepository(reservation::class)->find($id);
    $entityManager->remove($reservation);
    $entityManager->flush();

    return $this->redirectToRoute("res");
}
}
