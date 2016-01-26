<?php

namespace Enm\TransformerBundle\DependencyInjection;

use Enm\Transformer\Configuration\TransformerConfiguration;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnmTransformerExtension extends Extension
{

    /**
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new TransformerConfiguration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
          $container,
          new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        $this->setGlobalConfig($config, $container);
    }


    /**
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function setGlobalConfig(
      array $config,
      ContainerBuilder $container
    ) {
        $config_array = array();
        if (is_array($config)) {
            foreach ($config as $key => $settings) {
                $config_array[$key] = $settings;
            }
        }
        $container->setParameter('transformer.config', $config_array);
    }
}
