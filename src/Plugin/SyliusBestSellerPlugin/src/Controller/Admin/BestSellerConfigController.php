<?php

declare(strict_types=1);

namespace SyliusBestSellerPlugin\Controller\Admin;

use SyliusBestSellerPlugin\Entity\BestSellerConfig;
use SyliusBestSellerPlugin\Form\Type\BestSellerConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class BestSellerConfigController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateAction(Request $request): Response
    {
        $config = $this->entityManager->getRepository(BestSellerConfig::class)->findOneBy([]);

        if (!$config) {
            $config = new BestSellerConfig();
            $this->entityManager->persist($config);
        }

        $form = $this->createForm(BestSellerConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'sylius_best_seller.ui.config_saved');

            return $this->redirectToRoute('sylius_best_seller_admin_config');
        }

        return $this->render('@SyliusBestSellerPlugin/admin/best_seller/config.html.twig', [
            'form' => $form->createView(),
            'config' => $config,
        ]);
    }
}
