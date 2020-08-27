<?php

namespace Hanwoolderink88\ApiForm\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        if ($rootNode instanceof ArrayNodeDefinition) {
            $children = $rootNode->addDefaultsIfNotSet()->children();
            $children->scalarNode('test_var')->defaultNull()->end();
            $children->scalarNode('test_var2')->defaultValue('hello world')->end();
            $children->end();
        }

        return $treeBuilder;
    }
}
