<?php

namespace Enhavo\Bundle\ShopBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Sylius\Component\Resource\Factory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoShopExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        $container->setParameter('enhavo_shop.mailer.confirm', $config['mailer']['confirm']);
        $container->setParameter('enhavo_shop.mailer.tracking', $config['mailer']['tracking']);
        $container->setParameter('enhavo_shop.mailer.notification', $config['mailer']['notification']);

        $container->setParameter('enhavo_shop.document.billing', $config['document']['billing']);
        $container->setParameter('enhavo_shop.document.packing_slip', $config['document']['packing_slip']);

        $container->setParameter('enhavo_shop.payment.paypal.logo', $config['payment']['paypal']['logo']);
        $container->setParameter('enhavo_shop.payment.paypal.branding', $config['payment']['paypal']['branding']);

        $configFiles = array(
            'services/services.yml',
            'services/order.yml',
            'services/form.yml'
        );
        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }
}