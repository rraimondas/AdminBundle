<?php

namespace Platform\Bundle\AdminBundle\DependencyInjection;

use Platform\Bundle\AdminBundle\Model\AdminUser;
use Platform\Bundle\AdminBundle\Model\AdminUserInterface;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class PlatformAdminExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration($config, $container), $config);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->registerResources('sylius', $config['driver'], $config['resources'], $container);

        $loader->load('services.xml');
    }
}
