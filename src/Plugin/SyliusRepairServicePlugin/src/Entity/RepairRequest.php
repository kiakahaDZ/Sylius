<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Entity;

use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Uid\Uuid;

class RepairRequest
{
    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_DIAGNOSED = 'diagnosed';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    private ?int $id = null;

    private string $code;

    private ?CustomerInterface $customer = null;

    private string $deviceName = '';

    private ?string $deviceBrand = null;

    private ?string $deviceModel = null;

    private string $issueDescription = '';

    private string $status = self::STATUS_SUBMITTED;

    private string $customerName = '';

    private string $customerEmail = '';

    private ?string $customerPhoneNumber = null;

    private ?string $diagnosisNotes = null;

    private ?string $repairNotes = null;

    private \DateTimeInterface $createdAt;

    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->code = Uuid::v7()->toRfc4122();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getDeviceName(): string
    {
        return $this->deviceName;
    }

    public function setDeviceName(string $deviceName): void
    {
        $this->deviceName = $deviceName;
    }

    public function getDeviceBrand(): ?string
    {
        return $this->deviceBrand;
    }

    public function setDeviceBrand(?string $deviceBrand): void
    {
        $this->deviceBrand = $deviceBrand;
    }

    public function getDeviceModel(): ?string
    {
        return $this->deviceModel;
    }

    public function setDeviceModel(?string $deviceModel): void
    {
        $this->deviceModel = $deviceModel;
    }

    public function getIssueDescription(): string
    {
        return $this->issueDescription;
    }

    public function setIssueDescription(string $issueDescription): void
    {
        $this->issueDescription = $issueDescription;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): void
    {
        $this->customerName = $customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(string $customerEmail): void
    {
        $this->customerEmail = $customerEmail;
    }

    public function getCustomerPhoneNumber(): ?string
    {
        return $this->customerPhoneNumber;
    }

    public function setCustomerPhoneNumber(?string $customerPhoneNumber): void
    {
        $this->customerPhoneNumber = $customerPhoneNumber;
    }

    public function getDiagnosisNotes(): ?string
    {
        return $this->diagnosisNotes;
    }

    public function setDiagnosisNotes(?string $diagnosisNotes): void
    {
        $this->diagnosisNotes = $diagnosisNotes;
    }

    public function getRepairNotes(): ?string
    {
        return $this->repairNotes;
    }

    public function setRepairNotes(?string $repairNotes): void
    {
        $this->repairNotes = $repairNotes;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
