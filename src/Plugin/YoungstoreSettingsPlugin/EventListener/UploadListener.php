<?php

namespace YoungstoreSettingsPlugin\EventListener;

use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use YoungstoreSettingsPlugin\Entity\StoreSettings;

final class UploadListener
{
    private string $uploadDestination;

    public function __construct(string $uploadDestination)
    {
        $this->uploadDestination = $uploadDestination;
    }

    public function uploadFiles(GenericEvent $event): void
    {
        $subject = $event->getSubject();

        if (!$subject instanceof StoreSettings) {
            throw new UnexpectedTypeException($subject, StoreSettings::class);
        }

        $this->handleUpload($subject, 'getFrontstoreLogo', 'setFrontstoreLogo');
        $this->handleUpload($subject, 'getAdminLogo', 'setAdminLogo');
        $this->handleUpload($subject, 'getFooterLogo', 'setFooterLogo');
        $this->handleUpload($subject, 'getNewCollectionImage1', 'setNewCollectionImage1');
        $this->handleUpload($subject, 'getNewCollectionImage2', 'setNewCollectionImage2');
        $this->handleUpload($subject, 'getNewCollectionImage3', 'setNewCollectionImage3');
    }

    private function handleUpload(StoreSettings $settings, string $getter, string $setter): void
    {
        $file = $settings->$getter();

        if ($file instanceof UploadedFile) {
            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move($this->uploadDestination, $filename);
            
            // Set the path to be saved in DB
            $settings->$setter('media/settings/' . $filename);
        } elseif ($file === null) {
            // Keep existing if no new file is uploaded
            // Form sends null if file field is empty and not required
        }
    }
}
