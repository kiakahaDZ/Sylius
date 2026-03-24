<?php

declare(strict_types=1);

namespace SyliusOffersPlugin\Controller\Admin;

use SyliusOffersPlugin\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends AbstractController
{
    private ?OfferRepository $offerRepository = null;
    private ?\Doctrine\ORM\EntityManagerInterface $entityManager = null;

    public function setEntityManager(\Doctrine\ORM\EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    private function getEntityManager(): \Doctrine\ORM\EntityManagerInterface
    {
        if (null === $this->entityManager) {
             throw new \LogicException('EntityManager not injected.');
        }
        return $this->entityManager;
    }

    public function setOfferRepository(OfferRepository $offerRepository): void
    {
        $this->offerRepository = $offerRepository;
    }

    private function getOfferRepository(): OfferRepository
    {
        if (null === $this->offerRepository) {
             throw new \LogicException('OfferRepository not injected.');
        }
        return $this->offerRepository;
    }
    public function indexAction(Request $request): Response
    {
        return $this->render('@SyliusOffersPlugin/admin/offer/index.html.twig', [
            'resources' => $this->getOfferRepository()->findAll(),
            'offers_count' => $this->getOfferRepository()->count([]),
            'active_offers_count' => count($this->getOfferRepository()->findActiveOffers()),
            'upcoming_offers_count' => count($this->getOfferRepository()->findUpcomingOffers(7)),
            'expiring_offers_count' => count($this->getOfferRepository()->findExpiringOffers(7)),
        ]);
    }

    public function createAction(Request $request): Response
    {
        $offer = new \SyliusOffersPlugin\Entity\Offer();
        $form = $this->createForm(\SyliusOffersPlugin\Form\Type\OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->persist($offer);
            $this->getEntityManager()->flush();

            $this->addFlash('success', 'sylius_offers.ui.offer_created_successfully');

            return $this->redirectToRoute('sylius_admin_offer_index');
        }

        return $this->render('@SyliusOffersPlugin/admin/offer/create.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    public function updateAction(Request $request, int $id): Response
    {
        $offer = $this->getOfferRepository()->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        $form = $this->createForm(\SyliusOffersPlugin\Form\Type\OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->flush();

            $this->addFlash('success', 'sylius_offers.ui.offer_updated_successfully');

            return $this->redirectToRoute('sylius_admin_offer_index');
        }

        return $this->render('@SyliusOffersPlugin/admin/offer/update.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    public function deleteAction(Request $request, int $id): Response
    {
        $offer = $this->getOfferRepository()->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found.');
        }

        if ($this->isCsrfTokenValid((string)$id, $request->request->get('_csrf_token'))) {
            $this->getEntityManager()->remove($offer);
            $this->getEntityManager()->flush();

            $this->addFlash('success', 'sylius_offers.ui.offer_deleted_successfully');
        } else {
            $this->addFlash('error', 'sylius.ui.csrf_token_is_invalid');
        }

        return $this->redirectToRoute('sylius_admin_offer_index');
    }
    
    public function statsAction(Request $request): Response
    {
        $stats = [
            'total' => $this->getOfferRepository()->count([]),
            'active' => count($this->getOfferRepository()->findActiveOffers()),
            'upcoming' => count($this->getOfferRepository()->findUpcomingOffers(7)),
            'expiring' => count($this->getOfferRepository()->findExpiringOffers(7)),
            'best_performing' => $this->getOfferRepository()->findBestPerformingOffers(5),
        ];

        return $this->json($stats);
    }
}