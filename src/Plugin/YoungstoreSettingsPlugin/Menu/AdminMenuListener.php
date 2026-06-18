<?php
namespace YoungstoreSettingsPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addSettingsMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $configuration = $menu->getChild('configuration');
        
        if (null !== $configuration) {
            $configuration
                ->addChild('youngstore_settings', ['route' => 'youngstore_settings_admin_store_settings_index'])
                ->setLabel('Youngstore Settings')
                ->setLabelAttribute('icon', 'tabler:settings');
        }
    }
}