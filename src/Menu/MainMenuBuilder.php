<?php

namespace Platform\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MainMenuBuilder
{
    const EVENT_NAME = 'platform_admin.menu.main';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param FactoryInterface $factory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param array $options
     *
     * @return ItemInterface
     */
    public function createMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $this->eventDispatcher->dispatch(self::EVENT_NAME, new MenuBuilderEvent($this->factory, $menu));

        $this->addConfigurationSubMenu($menu);

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     */
    private function addConfigurationSubMenu(ItemInterface $menu)
    {
        $configuration = $menu
            ->addChild('configuration')
            ->setLabel('platform.admin.menu.main.configuration.header');

        $configuration
            ->addChild('locales', ['route' => 'sylius_admin_locale_index'])
            ->setLabel('platform.admin.menu.main.configuration.locales')
            ->setLabelAttribute('icon', 'translate')
        ;

        $configuration
            ->addChild('admin_users', ['route' => 'sylius_admin_admin_user_index'])
            ->setLabel('platform.admin.menu.main.configuration.admin_users')
            ->setLabelAttribute('icon', 'lock')
        ;
    }
}
