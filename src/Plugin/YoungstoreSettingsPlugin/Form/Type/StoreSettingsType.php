<?php
namespace YoungstoreSettingsPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('frontstoreLogo', FileType::class, ['required' => false, 'label' => 'Frontstore Logo', 'data_class' => null])
            ->add('adminLogo', FileType::class, ['required' => false, 'label' => 'Admin Logo', 'data_class' => null])
            ->add('apkLink', UrlType::class, ['required' => false, 'label' => 'Google Play APK Link'])
            
            // --- Footer ---
            ->add('footerLogo', FileType::class, ['required' => false, 'label' => 'Footer Logo', 'data_class' => null])
            ->add('footerAboutText', TextareaType::class, ['required' => false, 'label' => 'Footer About Text'])
            ->add('contactAddress', TextType::class, ['required' => false, 'label' => 'Contact Address'])
            ->add('contactPhone', TextType::class, ['required' => false, 'label' => 'Contact Phone'])
            ->add('contactEmail', TextType::class, ['required' => false, 'label' => 'Contact Email'])
            
            // --- Social Networks ---
            ->add('socialFacebookEnabled', CheckboxType::class, ['required' => false, 'label' => 'Enable Facebook'])
            ->add('socialFacebookUrl', UrlType::class, ['required' => false, 'label' => 'Facebook URL'])
            ->add('socialInstagramEnabled', CheckboxType::class, ['required' => false, 'label' => 'Enable Instagram'])
            ->add('socialInstagramUrl', UrlType::class, ['required' => false, 'label' => 'Instagram URL'])
            ->add('socialTiktokEnabled', CheckboxType::class, ['required' => false, 'label' => 'Enable TikTok'])
            ->add('socialTiktokUrl', UrlType::class, ['required' => false, 'label' => 'TikTok URL'])
            ->add('socialWhatsappEnabled', CheckboxType::class, ['required' => false, 'label' => 'Enable WhatsApp'])
            ->add('socialWhatsappUrl', UrlType::class, ['required' => false, 'label' => 'WhatsApp URL'])
            
            // --- New Collection ---
            ->add('newCollectionTitle', TextType::class, ['required' => false, 'label' => 'New Collection Section Title'])
            ->add('newCollectionImage1', FileType::class, ['required' => false, 'label' => 'New Collection Image 1', 'data_class' => null])
            ->add('newCollectionImage2', FileType::class, ['required' => false, 'label' => 'New Collection Image 2', 'data_class' => null])
            ->add('newCollectionImage3', FileType::class, ['required' => false, 'label' => 'New Collection Image 3', 'data_class' => null]);
    }

    public function getBlockPrefix(): string
    {
        return 'youngstore_settings';
    }
}
