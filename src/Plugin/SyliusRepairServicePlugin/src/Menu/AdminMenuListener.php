<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addRepairRequestsMenu')]
final class AdminMenuListener
{
    public function addRepairRequestsMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $sales = $menu->getChild('sales');

        if (null === $sales) {
            return;
        }

        $sales
            ->addChild('repair_requests', ['route' => 'sylius_admin_repair_request_index'])
            ->setLabel('sylius_repair_service.ui.repair_requests')
            ->setLabelAttribute('icon', 'tabler:tool')
        ;
    }
}
