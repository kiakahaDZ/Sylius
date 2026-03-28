<?php

declare(strict_types=1);

namespace SyliusYalidinePlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configuration form for the Yalidine shipping calculator.
 *
 * The admin can optionally set a fixed amount.
 * If left at 0, the calculator will dynamically fetch fees from Yalidine API.
 */
final class YalidineCalculatorConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('amount', IntegerType::class, [
            'label' => 'sylius_yalidine.form.calculator.amount',
            'required' => false,
            'attr' => [
                'placeholder' => '0 = dynamic from Yalidine API',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_shipping_calculator_yalidine';
    }
}
