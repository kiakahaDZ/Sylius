<?php

declare(strict_types=1);

namespace SyliusOffersPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addOffersMenu')]
final class AdminMenuListener
{
    public function addOffersMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $marketing = $menu->getChild('marketing');

        if (null === $marketing) {
            return;
        }

        $marketing
            ->addChild('offers', ['route' => 'sylius_admin_offer_index'])
            ->setLabel('sylius_offers.ui.offers')
            ->setLabelAttribute('icon', 'tabler:tag')
        ;
    }
}
