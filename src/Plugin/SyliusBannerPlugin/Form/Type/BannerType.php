<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Form\Type;

use Plugin\SyliusBannerPlugin\Entity\BannerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

final class BannerType extends AbstractType
{
    public function __construct(
        private readonly \Plugin\SyliusBannerPlugin\Form\EventSubscriber\BannerImageSubscriber $imageSubscriber,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'sylius_banner.ui.title',
                'required' => false,
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'sylius_banner.ui.subtitle',
                'required' => false,
            ])
            ->add('link', TextType::class, [
                'label' => 'sylius_banner.ui.link',
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'sylius_banner.ui.image',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '5M']),
                ],
            ])
            ->add('position', TextType::class, [
                'label' => 'sylius_banner.ui.position',
                'required' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
                'required' => false,
            ])
            ->add('sortOrder', IntegerType::class, [
                'label' => 'sylius_banner.ui.sort_order',
                'required' => true,
            ])
        ;

        $builder->addEventSubscriber($this->imageSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BannerInterface::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_banner';
    }
}
