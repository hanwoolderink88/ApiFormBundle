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

        return $treeBuilder;
    }
}
