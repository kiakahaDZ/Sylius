<?php

declare(strict_types=1);

namespace SyliusOffersPlugin\Controller\Admin;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends ResourceController
{
    public function indexAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);
        
        $view = View::create()
            ->setTemplate($configuration->getTemplate('index.html'))
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resources' => $resources,
                'offers_count' => $this->repository->count([]),
                'active_offers_count' => count($this->repository->findActiveOffers()),
            ]);
        
        return $this->viewHandler->handle($configuration, $view);
    }
    
    public function statsAction(Request $request): Response
    {
        $stats = [
            'total' => $this->repository->count([]),
            'active' => count($this->repository->findActiveOffers()),
            'upcoming' => count($this->repository->findUpcomingOffers(7)),
            'expiring' => count($this->repository->findExpiringOffers(7)),
            'best_performing' => $this->repository->findBestPerformingOffers(5),
        ];
        
        return $this->json($stats);
    }
}