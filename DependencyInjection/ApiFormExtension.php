<?php

namespace Hanwoolderink88\ApiForm\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ApiFormExtension extends Extension
{
    /**
     * @param mixed[]|array $configs
     * @param ContainerBuilder $container
     * @return void
     * @codeCoverageIgnore
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('request-service.xml');

        $container->setParameter('api_form.test_var', $config['test_var']);
        $container->setParameter('api_form.test_var2', $config['test_var2']);
    }
}
