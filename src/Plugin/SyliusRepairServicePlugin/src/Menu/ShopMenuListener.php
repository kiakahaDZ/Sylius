<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class ShopMenuListener
{
    #[AsEventListener(event: 'sylius.menu.shop.main')]
    public function addMainMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('repair_service', ['route' => 'sylius_repair_service_shop_request'])
            ->setLabel('sylius_repair_service.ui.repair_service')
            ->setLabelAttribute('icon', 'tabler:tool')
        ;
    }

    #[AsEventListener(event: 'sylius.menu.shop.account')]
    public function addAccountMenuItems(MenuBuilderEvent $event): void
    {
        // Optional: add to user account menu if needed
    }
}
