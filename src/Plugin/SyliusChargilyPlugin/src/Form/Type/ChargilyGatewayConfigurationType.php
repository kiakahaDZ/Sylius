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
            ->add('sandbox', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class, [
                'label' => 'sylius_chargily.form.sandbox',
                'required' => false,
            ])
            ->add('live_public_key', TextType::class, [
                'label' => 'sylius_chargily.form.live_public_key',
                'required' => false,
            ])
            ->add('live_secret_key', TextType::class, [
                'label' => 'sylius_chargily.form.live_secret_key',
                'required' => false,
            ])
            ->add('test_public_key', TextType::class, [
                'label' => 'sylius_chargily.form.test_public_key',
                'required' => false,
            ])
            ->add('test_secret_key', TextType::class, [
                'label' => 'sylius_chargily.form.test_secret_key',
                'required' => false,
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
            ->add('themes_js', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['style' => 'display:none;'],
                'help' => '<script>
                    (function() {
                        const initChargilyToggle = () => {
                            const sandboxInput = document.querySelector("[id$=_sandbox]");
                            if (!sandboxInput) return;
                            
                            const checkboxContainer = sandboxInput.closest(".ui.checkbox");
                            if (checkboxContainer) {
                                checkboxContainer.classList.add("toggle");
                            }
                            
                            const toggleFields = () => {
                                const isSandbox = sandboxInput.checked;
                                document.querySelectorAll("[id*=test_public_key], [id*=test_secret_key]").forEach(el => {
                                    const field = el.closest(".field");
                                    if (field) field.style.display = isSandbox ? "block" : "none";
                                });
                                document.querySelectorAll("[id*=live_public_key], [id*=live_secret_key]").forEach(el => {
                                    const field = el.closest(".field");
                                    if (field) field.style.display = isSandbox ? "none" : "block";
                                });
                            };
                            
                            sandboxInput.addEventListener("change", toggleFields);
                            toggleFields();
                        };
                        
                        if (document.readyState === "loading") {
                            document.addEventListener("DOMContentLoaded", initChargilyToggle);
                        } else {
                            initChargilyToggle();
                        }
                    })();
                </script>',
                'help_html' => true,
                'label' => false,
            ])
        ;
    }
}

