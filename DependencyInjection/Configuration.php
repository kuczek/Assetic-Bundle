<?php

namespace Hexmedia\AsseticBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hexmedia_assetic');

        $rootNode
            ->isRequired()
            ->children()
                ->scalarNode('read_from')->defaultValue(null)->end()
                ->scalarNode('write_to')->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}
