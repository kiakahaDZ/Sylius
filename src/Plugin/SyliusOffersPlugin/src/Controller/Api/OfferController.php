<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends AbstractController
{
    private OfferRepository $offerRepository;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * @Rest\Get("/offers")
     */
    public function getOffers(Request $request): Response
    {
        $type = $request->query->get('type');
        $limit = (int)$request->query->get('limit', 10);
        $url = $request->query->get('url', '/');
        $locale = $request->query->get('locale');

        if ($type) {
            $offers = $this->offerRepository->findActiveByType($type, $limit);
        }
        else {
            $offers = $this->offerRepository->findActiveOffers(null, $url, $locale);
            $offers = array_slice($offers, 0, $limit);
        }

        $response = [];
        foreach ($offers as $offer) {
            $response[] = $this->serializeOffer($offer);
        }

        return $this->json([
            'status' => 'success',
            'data' => $response,
            'meta' => [
                'total' => count($response),
                'limit' => $limit,
                'type' => $type,
            ]
        ]);
    }

    /**
     * @Rest\Get("/offers/{id}")
     */
    public function getOffer(int $id): Response
    {
        $offer = $this->offerRepository->find($id);

        if (!$offer) {
            return $this->json([
                'status' => 'error',
                'message' => 'Offer not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Increment view count
        $this->offerRepository->incrementViews($id);

        return $this->json([
            'status' => 'success',
            'data' => $this->serializeOffer($offer),
        ]);
    }

    /**
     * @Rest\Post("/offers/{id}/click")
     */
    public function trackClick(int $id): Response
    {
        $offer = $this->offerRepository->find($id);

        if (!$offer) {
            return $this->json([
                'status' => 'error',
                'message' => 'Offer not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $this->offerRepository->incrementClicks($id);

        return $this->json([
            'status' => 'success',
            'message' => 'Click tracked',
            'data' => [
                'redirect_url' => $offer->getLink(),
            ]
        ]);
    }

    /**
     * @Rest\Get("/offers/upcoming")
     */
    public function getUpcomingOffers(Request $request): Response
    {
        $days = (int)$request->query->get('days', 7);
        $offers = $this->offerRepository->findUpcomingOffers($days);

        $response = [];
        foreach ($offers as $offer) {
            $response[] = $this->serializeOffer($offer);
        }

        return $this->json([
            'status' => 'success',
            'data' => $response,
            'meta' => [
                'days' => $days,
                'total' => count($response),
            ]
        ]);
    }

    /**
     * @Rest\Get("/offers/expiring")
     */
    public function getExpiringOffers(Request $request): Response
    {
        $days = (int)$request->query->get('days', 7);
        $offers = $this->offerRepository->findExpiringOffers($days);

        $response = [];
        foreach ($offers as $offer) {
            $response[] = $this->serializeOffer($offer);
        }

        return $this->json([
            'status' => 'success',
            'data' => $response,
            'meta' => [
                'days' => $days,
                'total' => count($response),
            ]
        ]);
    }

    /**
     * @Rest\Get("/offers/best-performing")
     */
    public function getBestPerformingOffers(Request $request): Response
    {
        $limit = (int)$request->query->get('limit', 10);
        $offers = $this->offerRepository->findBestPerformingOffers($limit);

        $response = [];
        foreach ($offers as $offer) {
            $response[] = $this->serializeOffer($offer);
        }

        return $this->json([
            'status' => 'success',
            'data' => $response,
            'meta' => [
                'limit' => $limit,
                'total' => count($response),
            ]
        ]);
    }

    private function serializeOffer($offer): array
    {
        $data = [
            'id' => $offer->getId(),
            'code' => $offer->getCode(),
            'title' => $offer->getTitle(),
            'description' => $offer->getDescription(),
            'image' => $offer->getImage(),
            'link' => $offer->getLink(),
            'badge' => $offer->getBadge(),
            'type' => $offer->getType(),
            'position' => $offer->getPosition(),
            'enabled' => $offer->isEnabled(),
            'is_active' => $offer->isActive(),
            'views' => $offer->getViews(),
            'clicks' => $offer->getClicks(),
            'conversion_rate' => $offer->getConversionRate(),
            'styles' => [
                'background_color' => $offer->getBackgroundColor(),
                'text_color' => $offer->getTextColor(),
                'button_text' => $offer->getButtonText(),
                'button_color' => $offer->getButtonColor(),
            ],
        ];

        if ($offer->getStartDate()) {
            $data['start_date'] = $offer->getStartDate()->format(\DateTime::RFC3339);
        }

        if ($offer->getEndDate()) {
            $data['end_date'] = $offer->getEndDate()->format(\DateTime::RFC3339);
            $data['remaining_time'] = $this->calculateRemainingTime($offer->getEndDate());
        }

        return $data;
    }

    private function calculateRemainingTime(\DateTimeInterface $endDate): array
    {
        $now = new \DateTime();
        $diff = $endDate->diff($now);

        return [
            'days' => $diff->d,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s,
            'total_seconds' => ($diff->d * 86400) + ($diff->h * 3600) + ($diff->i * 60) + $diff->s,
            'is_expired' => $endDate <= $now,
        ];
    }
}