<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Form\Type;

use SyliusRepairServicePlugin\Entity\RepairRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RepairRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deviceName', TextType::class, [
                'label' => 'sylius_repair_service.form.device_name',
                'constraints' => [new NotBlank(), new Length(max: 255)],
            ])
            ->add('deviceBrand', TextType::class, [
                'label' => 'sylius_repair_service.form.device_brand',
                'required' => false,
            ])
            ->add('deviceModel', TextType::class, [
                'label' => 'sylius_repair_service.form.device_model',
                'required' => false,
            ])
            ->add('issueDescription', TextareaType::class, [
                'label' => 'sylius_repair_service.form.issue_description',
                'constraints' => [new NotBlank()],
                'attr' => ['rows' => 6],
            ])
            ->add('customerName', TextType::class, [
                'label' => 'sylius_repair_service.form.customer_name',
                'constraints' => [new NotBlank(), new Length(max: 255)],
            ])
            ->add('customerEmail', EmailType::class, [
                'label' => 'sylius_repair_service.form.customer_email',
                'constraints' => [new NotBlank(), new Email(), new Length(max: 255)],
            ])
            ->add('customerPhoneNumber', TextType::class, [
                'label' => 'sylius_repair_service.form.customer_phone_number',
                'required' => false,
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
