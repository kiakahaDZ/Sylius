<?php

declare(strict_types=1);

namespace SyliusChargilyPlugin\Form\Type;

use Sylius\Bundle\PaymentBundle\Attribute\AsGatewayConfigurationType;
use SyliusChargilyPlugin\Provider\ChargilyGatewayFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

#[AsGatewayConfigurationType(type: ChargilyGatewayFactory::FACTORY_NAME, label: 'sylius_chargily.form.gateway_factory_label', priority: 0)]
final class ChargilyGatewayConfigurationType extends AbstractType
{
    public function __construct(private readonly ChargilyGatewayFactory $chargilyGatewayFactory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'sylius_chargily.form.mode.test' => 'test',
                    'sylius_chargily.form.mode.live' => 'live',
                ],
                'label' => 'sylius_chargily.form.mode.label',
            ])
            ->add('public_key', TextType::class, [
                'label' => 'sylius_chargily.form.public_key',
            ])
            ->add('secret_key', TextType::class, [
                'label' => 'sylius_chargily.form.secret_key',
            ])
            ->add('payment_method', ChoiceType::class, [
                'choices' => array_flip($this->chargilyGatewayFactory->getSupportedPaymentMethods()),
                'label' => 'sylius_chargily.form.payment_method.label',
            ])
            ->add('locale', ChoiceType::class, [
                'choices' => [
                    'sylius_chargily.form.locale.ar' => 'ar',
                    'sylius_chargily.form.locale.en' => 'en',
                ],
                'label' => 'sylius_chargily.form.locale.label',
            ])
            ->add('fees_allocation', ChoiceType::class, [
                'choices' => [
                    'sylius_chargily.form.fees_allocation.customer' => 'customer',
                    'sylius_chargily.form.fees_allocation.merchant' => 'merchant',
                ],
                'label' => 'sylius_chargily.form.fees_allocation.label',
            ])
        ;
    }
}
