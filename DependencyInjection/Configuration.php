<?php

namespace Hanwoolderink88\ApiForm\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder|void
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('hanwoolderink88_apiform');
        $rootNode = $treeBuilder->getRootNode();

        /** @phpstan-ignore-next-line */
        $nodeBuilder = $rootNode->addDefaultsIfNotSet()->children();

        $nodeBuilder
            ->scalarNode('test_var')
            ->defaultNull()
            ->end();

        $nodeBuilder
            ->scalarNode('test_var2')
            ->defaultValue('hello world')
            ->end();
    }
}
