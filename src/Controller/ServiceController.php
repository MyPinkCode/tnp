<?php

namespace App\Controller;

use App\Form\SerFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\Service;

class ServiceController extends AbstractController
{
    /**
     * @Route("/serv", name="serv")
     */
    public function index()
    { $repo=$this->getDoctrine()->getRepository(Service::class);
        $Services=$repo->findAll();
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServController',
            'services'=>$Services
        ]);
    }
    /**
     * @Route("/new1", name="add_service")
     */
    public function addservice(Request $request): Response
    {$service = new service();
        $form = $this->createForm(SerFormType::class);
        $form->handleRequest($request);
  
        if($form->isSubmitted() && $form->isValid()) {
          $service = $form->getData();
  
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($service);
          $entityManager->flush();
  
          return $this->redirectToRoute('serv');
        }
        return $this->render("service/new1.html.twig", [
            "form_title" => "Ajouter un service",
            "form_service" => $form->createView(),
        ]);
    }
    /**
 * @Route("/new1/{id}", name="modify1")
 */
public function modifyservice(Request $request, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $service = $entityManager->getRepository(Service::class)->find($id);
    $form = $this->createForm(SerFormType::class, $service);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $entityManager->flush();
        return $this->redirectToRoute('serv');
    }

    return $this->render("service/new1.html.twig", [
        "form_title" => "Modifier service",
        "form_service" => $form->createView(),
    ]);
}
/**
 * @Route("/delete1/{id}", name="delete_service")
 */
public function deleteservice(int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $service = $entityManager->getRepository(Service::class)->find($id);
    $entityManager->remove($service);
    $entityManager->flush();

    return $this->redirectToRoute("serv");
}
}
