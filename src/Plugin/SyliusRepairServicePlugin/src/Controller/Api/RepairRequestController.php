<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use SyliusRepairServicePlugin\Entity\RepairRequest;
use SyliusRepairServicePlugin\Repository\RepairRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RepairRequestController extends AbstractController
{
    public function __construct(
        private readonly RepairRequestRepository $repairRequestRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function indexAction(Request $request): JsonResponse
    {
        $customerEmail = $request->query->getString('customerEmail');
        $repairRequests = $customerEmail !== '' ? $this->repairRequestRepository->findByCustomerEmail($customerEmail) : $this->repairRequestRepository->findLatest();

        return $this->json(array_map($this->normalize(...), $repairRequests));
    }

    public function showAction(string $code): JsonResponse
    {
        $repairRequest = $this->repairRequestRepository->findOneByCode($code);
        if (!$repairRequest instanceof RepairRequest) {
            throw new NotFoundHttpException(sprintf('Repair request with code %s was not found.', $code));
        }

        return $this->json($this->normalize($repairRequest));
    }

    public function createAction(Request $request): JsonResponse
    {
        $payload = $request->toArray();

        foreach (['customerEmail', 'customerName', 'deviceName', 'issueDescription'] as $requiredField) {
            if (!isset($payload[$requiredField]) || '' === trim((string) $payload[$requiredField])) {
                throw new BadRequestHttpException(sprintf('Missing required field: %s', $requiredField));
            }
        }

        $repairRequest = new RepairRequest();
        $repairRequest->setCustomerEmail((string) $payload['customerEmail']);
        $repairRequest->setCustomerName((string) $payload['customerName']);
        $repairRequest->setCustomerPhoneNumber(isset($payload['customerPhoneNumber']) ? (string) $payload['customerPhoneNumber'] : null);
        $repairRequest->setDeviceName((string) $payload['deviceName']);
        $repairRequest->setDeviceBrand(isset($payload['deviceBrand']) ? (string) $payload['deviceBrand'] : null);
        $repairRequest->setDeviceModel(isset($payload['deviceModel']) ? (string) $payload['deviceModel'] : null);
        $repairRequest->setIssueDescription((string) $payload['issueDescription']);
        $repairRequest->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($repairRequest);
        $this->entityManager->flush();

        return $this->json($this->normalize($repairRequest), Response::HTTP_CREATED);
    }

    /** @return array<string, mixed> */
    private function normalize(RepairRequest $repairRequest): array
    {
        return [
            'code' => $repairRequest->getCode(),
            'createdAt' => $repairRequest->getCreatedAt()->format(DATE_ATOM),
            'customerEmail' => $repairRequest->getCustomerEmail(),
            'customerName' => $repairRequest->getCustomerName(),
            'customerPhoneNumber' => $repairRequest->getCustomerPhoneNumber(),
            'deviceBrand' => $repairRequest->getDeviceBrand(),
            'deviceModel' => $repairRequest->getDeviceModel(),
            'deviceName' => $repairRequest->getDeviceName(),
            'diagnosisNotes' => $repairRequest->getDiagnosisNotes(),
            'issueDescription' => $repairRequest->getIssueDescription(),
            'repairNotes' => $repairRequest->getRepairNotes(),
            'status' => $repairRequest->getStatus(),
            'updatedAt' => $repairRequest->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}
