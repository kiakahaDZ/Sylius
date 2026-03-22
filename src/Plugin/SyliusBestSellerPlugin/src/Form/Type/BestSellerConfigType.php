<?php

declare(strict_types = 1)
;

namespace SyliusBestSellerPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BestSellerConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayOnHomepage', CheckboxType::class , [
            'label' => 'sylius_best_seller.ui.display_on_homepage',
            'required' => false,
        ])
            ->add('numberOfProducts', IntegerType::class , [
            'label' => 'sylius_best_seller.ui.number_of_products',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Range(['min' => 1, 'max' => 50]),
            ],
        ])
            ->add('period', ChoiceType::class , [
            'label' => 'sylius_best_seller.ui.period',
            'choices' => [
                'sylius_best_seller.ui.daily' => 'daily',
                'sylius_best_seller.ui.weekly' => 'weekly',
                'sylius_best_seller.ui.monthly' => 'monthly',
                'sylius_best_seller.ui.yearly' => 'yearly',
                'sylius_best_seller.ui.all_time' => 'all_time',
            ],
        ])
            ->add('sortBy', ChoiceType::class , [
            'label' => 'sylius_best_seller.ui.sort_by',
            'choices' => [
                'sylius_best_seller.ui.total_sales' => 'total_sales',
                'sylius_best_seller.ui.total_quantity' => 'total_quantity',
                'sylius_best_seller.ui.total_revenue' => 'total_revenue',
            ],
        ])
            ->add('cacheTtl', IntegerType::class , [
            'label' => 'sylius_best_seller.ui.cache_ttl',
            'help' => 'sylius_best_seller.ui.cache_ttl_help',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Range(['min' => 60, 'max' => 86400]),
            ],
        ]);
    }
}