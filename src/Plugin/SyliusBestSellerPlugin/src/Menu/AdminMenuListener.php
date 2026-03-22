<?php

declare(strict_types=1);

namespace SyliusBestSellerPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addBestSellerMenu')]
final class AdminMenuListener
{
    public function addBestSellerMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalog = $menu->getChild('catalog');
        if ($catalog !== null) {
            $catalog
                ->addChild('best_sellers', ['route' => 'sylius_best_seller_admin_config'])
                ->setLabel('sylius_best_seller.ui.best_seller_settings')
                ->setLabelAttribute('icon', 'tabler:trending-up')
            ;
        }
    }
}
