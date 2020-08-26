<?php

namespace Hanwoolderink88\ApiForm\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('api_form');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode->addDefaultsIfNotSet()->children()
            ->scalarNode('test_var')->defaultNull()->end()
            ->scalarNode('test_var2')->defaultValue('hello world')->end()
            ->end();

        return $treeBuilder;
    }
}
