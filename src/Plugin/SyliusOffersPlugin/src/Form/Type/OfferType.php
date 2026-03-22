<?php

declare(strict_types = 1)
;

namespace SyliusOffersPlugin\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class OfferType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class , [
            'label' => 'sylius.ui.code',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255]),
            ],
        ])
            ->add('title', TextType::class , [
            'label' => 'sylius.ui.title',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255]),
            ],
        ])
            ->add('description', TextareaType::class , [
            'label' => 'sylius.ui.description',
            'required' => false,
            'attr' => ['rows' => 5],
        ])
            ->add('image', TextType::class , [
            'label' => 'sylius.ui.image',
            'required' => false,
            'help' => 'sylius_offers.ui.image_help',
        ])
            ->add('link', UrlType::class , [
            'label' => 'sylius.ui.link',
            'required' => false,
        ])
            ->add('badge', TextType::class , [
            'label' => 'sylius_offers.ui.badge',
            'required' => false,
            'help' => 'sylius_offers.ui.badge_help',
        ])
            ->add('type', ChoiceType::class , [
            'label' => 'sylius_offers.ui.offer_type',
            'choices' => [
                'sylius_offers.ui.card' => 'card',
                'sylius_offers.ui.banner' => 'banner',
                'sylius_offers.ui.popup' => 'popup',
                'sylius_offers.ui.sidebar' => 'sidebar',
            ],
            'required' => true,
        ])
            ->add('position', IntegerType::class , [
            'label' => 'sylius.ui.position',
            'required' => false,
            'data' => 0,
        ])
            ->add('startDate', DateTimeType::class , [
            'label' => 'sylius.ui.start_date',
            'required' => false,
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'yyyy-MM-dd HH:mm',
            'attr' => ['class' => 'datetimepicker'],
        ])
            ->add('endDate', DateTimeType::class , [
            'label' => 'sylius.ui.end_date',
            'required' => false,
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'yyyy-MM-dd HH:mm',
            'attr' => ['class' => 'datetimepicker'],
        ])
            ->add('targetUrls', CollectionType::class , [
            'label' => 'sylius_offers.ui.target_urls',
            'entry_type' => TextType::class ,
            'allow_add' => true,
            'allow_delete' => true,
            'required' => false,
            'help' => 'sylius_offers.ui.target_urls_help',
        ])
            ->add('targetProducts', CollectionType::class , [
            'label' => 'sylius_offers.ui.target_products',
            'entry_type' => TextType::class ,
            'allow_add' => true,
            'allow_delete' => true,
            'required' => false,
        ])
            ->add('targetCategories', CollectionType::class , [
            'label' => 'sylius_offers.ui.target_categories',
            'entry_type' => TextType::class ,
            'allow_add' => true,
            'allow_delete' => true,
            'required' => false,
        ])
            ->add('targetChannels', CollectionType::class , [
            'label' => 'sylius.ui.channels',
            'entry_type' => TextType::class ,
            'allow_add' => true,
            'allow_delete' => true,
            'required' => false,
        ])
            ->add('targetLocales', CollectionType::class , [
            'label' => 'sylius.ui.locales',
            'entry_type' => TextType::class ,
            'allow_add' => true,
            'allow_delete' => true,
            'required' => false,
        ])
            ->add('backgroundColor', TextType::class , [
            'label' => 'sylius_offers.ui.background_color',
            'required' => false,
            'attr' => ['type' => 'color'],
        ])
            ->add('textColor', TextType::class , [
            'label' => 'sylius_offers.ui.text_color',
            'required' => false,
            'attr' => ['type' => 'color'],
        ])
            ->add('buttonText', TextType::class , [
            'label' => 'sylius_offers.ui.button_text',
            'required' => false,
        ])
            ->add('buttonColor', TextType::class , [
            'label' => 'sylius_offers.ui.button_color',
            'required' => false,
            'attr' => ['type' => 'color'],
        ])
            ->add('enabled', CheckboxType::class , [
            'label' => 'sylius.ui.enabled',
            'required' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_offer';
    }
}