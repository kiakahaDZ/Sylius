<?php

declare(strict_types=1);

namespace Plugin\SyliusBannerPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main', method: 'addBannerMenu')]
final class AdminMenuListener
{
    public function addBannerMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalog = $menu->getChild('catalog');
        if ($catalog !== null) {
            $catalog
                ->addChild('banners', ['route' => 'sylius_banner_admin_banner_index'])
                ->setLabel('sylius_banner.ui.banners')
                ->setLabelAttribute('icon', 'tabler:photo')
            ;
        }
    }
}
