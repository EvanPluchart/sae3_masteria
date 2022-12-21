<?php

namespace App\Controller;

use App\Entity\CandidacyTER;
use App\Entity\TER;
use App\Form\CandidacyTERType;
use App\Form\TERType;
use App\Repository\CandidacyTERRepository;
use App\Repository\TERRepository;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TERController extends AbstractController
{
    /**
     * Affiche la liste des TER.
     */
    #[Route('/ter', name: 'app_ter')]
    public function index(TERRepository $TERRepository): Response
    {
        $lstTER = $TERRepository->search();

        return $this->render('ter/index.html.twig', [
            'lstTER' => $lstTER,
        ]);
    }

    /**
     * Affiche les informations d'un TER.
     */
    #[Route('/ter/{id}', name: 'app_ter_show', requirements: ['id' => '\d+'])]
    public function show(TER $TER): Response
    {
        return $this->render('ter/show.html.twig', [
            'TER' => $TER,
        ]);
    }

    /**
     * Formulaire de création d'un nouveau TER.
     */
    #[Route('/ter/create', name: 'app_ter_create')]
    public function createTER(TERRepository $TERRepository, Request $request): RedirectResponse|Response
    {
        $ter = new TER();

        $form = $this->createForm(TERType::class, $ter);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $TERRepository->save($ter, true);

            return $this->redirectToRoute('app_ter_show', ['id' => $ter->getId()]);
        }

        return $this->renderForm('ter/createTER.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Formulaire de modification d'un TER existant.
     */
    #[Route('/ter/{id}/update', name: 'app_ter_update')]
    public function updateTER(ManagerRegistry $doctrine, Request $request, TER $TER, int $id): RedirectResponse|Response
    {
        $form = $this->createForm(TERType::class, $TER);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            $entityManager->flush();

            return $this->redirectToRoute('app_ter_show', ['id' => $id]);
        }

        return $this->renderForm('ter/updateTER.html.twig', [
            'form' => $form,
            'ter' => $TER,
            'id' => $id,
        ]);
    }

    /**
     * Formulaire de deletion d'un TER.
     */
    #[Route('/ter/{id}/delete', name: 'app_ter_delete')]
    public function deleteTER(Request $request, TER $TER, TERRepository $service): Response
    {
        $form = $this->createForm(TERType::class, $TER, ['disabled' => true]);

        $deleteForm = $this->createFormBuilder($TER)
            ->add('delete', SubmitType::class, ['label' => 'Supprimer', 'attr' => ['class' => 'btn btn-primary']])
            ->add('cancel', SubmitType::class, ['label' => 'Annuler', 'attr' => ['class' => 'btn btn-secondary']])
            ->getForm();

        $deleteForm->handleRequest($request);
        if ($deleteForm->getClickedButton() && 'delete' === $deleteForm->getClickedButton()->getName()) {
            $service->remove($TER, true);

            return $this->redirectToRoute('app_ter');
        }

        if ($deleteForm->getClickedButton() && 'cancel' === $deleteForm->getClickedButton()->getName()) {
            return $this->redirectToRoute('app_ter_show', ['id' => $TER->getId()]);
        }

        return $this->renderForm('ter/deleteTER.html.twig', ['ter' => $TER, 'form' => $form, 'deleteForm' => $deleteForm]);
    }

    #[Route('/ter/create/candidacy', name: 'app_ter_create_candidacy')]
    public function createCandidacyTER(CandidacyTERRepository $candidacyTERRepository, Request $request): RedirectResponse|Response
    {
        $candidacyTER = new CandidacyTER();
        $form = $this->createForm(CandidacyTERType::class, $candidacyTER);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTimeImmutable('now');
            $candidacyTER->setDate($date);
            $candidacyTER->setStudent($this->getUser());
            $candidacyTERRepository->save($candidacyTER, true);

            return $this->redirectToRoute('app_ter_show', ['id' => $candidacyTER->getId()]);
        }

        return $this->renderForm('ter/createCandidacyTER.html.twig', [
            'form' => $form,
        ]);
    }
}
