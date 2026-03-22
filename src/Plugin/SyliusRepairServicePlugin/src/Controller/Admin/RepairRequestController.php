<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use SyliusRepairServicePlugin\Entity\RepairRequest;
use SyliusRepairServicePlugin\Form\Type\AdminRepairRequestType;
use SyliusRepairServicePlugin\Repository\RepairRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Workflow\WorkflowInterface;

final class RepairRequestController extends AbstractController
{
    public function __construct(
        private readonly RepairRequestRepository $repairRequestRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkflowInterface $repairRequestStateMachine,
    ) {
    }

    public function indexAction(): Response
    {
        return $this->render('@SyliusRepairServicePlugin/admin/repair_request/index.html.twig', [
            'repair_requests' => $this->repairRequestRepository->findLatest(),
        ]);
    }

    public function updateAction(Request $request, int $id): Response
    {
        $repairRequest = $this->repairRequestRepository->find($id);
        if (!$repairRequest instanceof RepairRequest) {
            throw new NotFoundHttpException(sprintf('Repair request with id %d was not found.', $id));
        }

        $originalStatus = $repairRequest->getStatus();
        $form = $this->createForm(AdminRepairRequestType::class, $repairRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->applyRequestedStatus($repairRequest, $originalStatus);
            $repairRequest->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            $this->addFlash('success', 'sylius_repair_service.ui.request_updated');

            return $this->redirectToRoute('sylius_admin_repair_request_index');
        }

        return $this->render('@SyliusRepairServicePlugin/admin/repair_request/update.html.twig', [
            'form' => $form->createView(),
            'repair_request' => $repairRequest,
        ]);
    }

    private function applyRequestedStatus(RepairRequest $repairRequest, string $originalStatus): void
    {
        $requestedStatus = $repairRequest->getStatus();
        if ($requestedStatus === $originalStatus) {
            return;
        }

        $repairRequest->setStatus($originalStatus);

        $transition = match ($requestedStatus) {
            RepairRequest::STATUS_DIAGNOSED => 'diagnose',
            RepairRequest::STATUS_IN_PROGRESS => 'start_progress',
            RepairRequest::STATUS_COMPLETED => 'complete',
            default => null,
        };

        if (null === $transition || !$this->repairRequestStateMachine->can($repairRequest, $transition)) {
            return;
        }

        $this->repairRequestStateMachine->apply($repairRequest, $transition);
    }
}
