<?php

namespace Daemon\FilestorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('daemon_filestorage');
        $this->addStorageConfig($rootNode);
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }

    private function addStorageConfig(ArrayNodeDefinition $rootNode) {
        $validStorageTypes = array('date', 'media');
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('storage_name')
                    ->defaultValue('storage')
                ->end()
                ->scalarNode('storage_type')
                    ->defaultValue('date')
                    ->validate()
                        ->ifNotInArray($validStorageTypes)
                        ->thenInvalid('Must choose one of '.json_encode($validStorageTypes))
                    ->end()
                ->end()
            ->end();

    }
}
