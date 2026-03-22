<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Form\EventSubscriber;

use Plugin\SyliusBannerPlugin\Entity\BannerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class BannerImageSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $imagesDir,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $banner = $event->getData();

        if (!$banner instanceof BannerInterface) {
            return;
        }

        $imageFile = $form->get('imageFile')->getData();
        if (!$imageFile instanceof UploadedFile) {
            return;
        }

        $uploadDir = rtrim($this->imagesDir, '/') . '/banner';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = sprintf('banner_%s_%s', uniqid(), $imageFile->getClientOriginalName());
        $imageFile->move($uploadDir, $filename);

        $banner->setImagePath('banner/' . $filename);
    }
}
