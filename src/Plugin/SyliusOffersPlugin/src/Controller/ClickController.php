<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Controller;

use SyliusOffersPlugin\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClickController extends AbstractController
{
    private OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function trackClick(int $id, Request $request): Response
    {
        $offer = $this->offerRepository->find($id);

        if (!$offer || !$offer->getLink()) {
            throw $this->createNotFoundException('Offer not found');
        }

        // Track click
        $this->offerRepository->incrementClicks($id);

        // Redirect to offer link
        return new RedirectResponse($offer->getLink());
    }
}