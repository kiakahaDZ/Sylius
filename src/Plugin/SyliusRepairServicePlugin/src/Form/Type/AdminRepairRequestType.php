<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Form\Type;

use SyliusRepairServicePlugin\Entity\RepairRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AdminRepairRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'sylius_repair_service.status.submitted' => RepairRequest::STATUS_SUBMITTED,
                    'sylius_repair_service.status.diagnosed' => RepairRequest::STATUS_DIAGNOSED,
                    'sylius_repair_service.status.in_progress' => RepairRequest::STATUS_IN_PROGRESS,
                    'sylius_repair_service.status.completed' => RepairRequest::STATUS_COMPLETED,
                ],
                'label' => 'sylius_repair_service.form.status',
            ])
            ->add('diagnosisNotes', TextareaType::class, [
                'label' => 'sylius_repair_service.form.diagnosis_notes',
                'required' => false,
                'attr' => ['rows' => 5],
            ])
            ->add('repairNotes', TextareaType::class, [
                'label' => 'sylius_repair_service.form.repair_notes',
                'required' => false,
                'attr' => ['rows' => 5],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RepairRequest::class,
        ]);
    }
}
