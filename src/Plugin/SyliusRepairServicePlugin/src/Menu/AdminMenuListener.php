<?php

declare(strict_types=1);

namespace SyliusRepairServicePlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addRepairRequestsMenu')]
final class AdminMenuListener
{
    public function addRepairRequestsMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        // Try the 'sales' section first (orders, payments, shipments)
        $parent = $menu->getChild('sales');

        // Fallback: create a dedicated top-level section
        if (null === $parent) {
            $parent = $menu
                ->addChild('repair_service_section')
                ->setLabel('sylius_repair_service.ui.repair_service')
                ->setLabelAttribute('icon', 'tabler:tool')
                ->setExtra('always_open', true)
            ;
        }

        $parent
            ->addChild('repair_requests', ['route' => 'sylius_admin_repair_request_index'])
            ->setLabel('sylius_repair_service.ui.repair_requests')
            ->setLabelAttribute('icon', 'tabler:tool')
        ;
    }
}
