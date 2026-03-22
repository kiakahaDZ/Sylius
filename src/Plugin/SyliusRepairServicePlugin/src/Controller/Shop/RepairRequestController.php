<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Controller\Shop;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use SyliusRepairServicePlugin\Entity\RepairRequest;
use SyliusRepairServicePlugin\Form\Type\RepairRequestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RepairRequestController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ShopperContextInterface $shopperContext,
    ) {
    }

    public function requestAction(Request $request): Response
    {
        $repairRequest = new RepairRequest();
        $this->prefillCustomerData($repairRequest);

        $form = $this->createForm(RepairRequestType::class, $repairRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repairRequest->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($repairRequest);
            $this->entityManager->flush();

            $this->addFlash('success', 'sylius_repair_service.ui.request_submitted');

            return $this->redirectToRoute('sylius_repair_service_shop_request_thank_you', [
                'code' => $repairRequest->getCode(),
            ]);
        }

        return $this->render('@SyliusRepairServicePlugin/shop/repair_request/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function thankYouAction(string $code): Response
    {
        return $this->render('@SyliusRepairServicePlugin/shop/repair_request/thank_you.html.twig', [
            'code' => $code,
        ]);
    }

    private function prefillCustomerData(RepairRequest $repairRequest): void
    {
        $customer = $this->shopperContext->getCustomer();
        if (!$customer instanceof CustomerInterface) {
            return;
        }

        $repairRequest->setCustomer($customer);
        $repairRequest->setCustomerName(trim(sprintf('%s %s', $customer->getFirstName() ?? '', $customer->getLastName() ?? '')) ?: ($customer->getFullName() ?? ''));
        $repairRequest->setCustomerEmail($customer->getEmail() ?? '');
        $repairRequest->setCustomerPhoneNumber($customer->getPhoneNumber());
    }
}
