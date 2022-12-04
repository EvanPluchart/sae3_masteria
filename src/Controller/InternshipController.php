<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Form\InternshipType;
use App\Repository\InternshipRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternshipController extends AbstractController
{
    /*
     * Renvoie la liste des stages à la vue twig
     */
    #[Route('/internship', name: 'app_internship')]
    public function index(InternshipRepository $service): Response
    {
        $internships = $service->findAll();
        return $this->render('internship/index.html.twig', ['internships' => $internships]);
    }

    #[Route('/internship/{id}', name: 'app_internship_show', requirements: ['id' => '\d+'])]
    public function show(Internship $internship): Response
    {
        return $this->render('internship/show.html.twig', ['internship' => $internship]);
    }

    #[Route('/internship/{id}/update', name: 'app_internship_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, ManagerRegistry $doctrine, Internship $internship): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(InternshipType::class, $internship);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_internship_show', ['id' => $internship->getId()]);
        }

        return $this->renderForm('internship/update.html.twig', ['contact' => $internship, 'form' => $form]);
    }

    #[Route('/internship/create', name: 'app_internship_create')]
    public function create(Request $request, InternshipRepository $service): Response
    {
        $internship = new Internship();
        $form = $this->createForm(InternshipType::class, $internship);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->save($internship, true);

            return $this->redirectToRoute('app_internship_show', ['id' => $internship->getId()]);
        }

        return $this->renderForm('internship/create.html.twig', ['form' => $form]);
    }

    #[Route('/internship/{id}/delete', name: 'app_internship_delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request, ManagerRegistry $doctrine, Internship $internship): Response
    {
        $entityManager = $doctrine->getManager();
        $form = $this->createFormBuilder($internship)
            ->add('delete', SubmitType::class, ['label' => 'Supprimer', 'attr' => ['class' => 'btn btn-primary']])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn btn-secondary']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->getClickedButton() && 'delete' === $form->getClickedButton()->getName()) {
            $entityManager->remove($internship);
            $entityManager->flush();

            return $this->redirectToRoute('app_internship');
        }

        if ($form->getClickedButton() && 'cancel' === $form->getClickedButton()->getName()) {
            return $this->redirectToRoute('app_internship_show', ['id' => $internship->getId()]);
        }

        return $this->renderForm('internship/delete.html.twig', ['contact' => $internship, 'form' => $form]);
    }
}