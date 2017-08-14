<?php

namespace Shapecode\Bundle\SwiftmailerPluginsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Shapecode\Bundle\SwiftmailerPluginsBundle\DependencyInjection
 * @author  Nikita Loges
 * @company tenolo GbR
 */
class Configuration implements ConfigurationInterface
{

    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('shapecode_swiftmailer_plugins');

        $rootNode
            ->beforeNormalization()
                ->ifTrue(function ($v) {
                    return is_array($v) && count($v) > 0 && !array_key_exists('mailers', $v);
                })
                ->then(function (array $v) {
                    $mailer = [];

                    foreach ($v as $key => $value) {
                        $mailer[$key] = $v[$key];
                        unset($v[$key]);
                    }

                    $v['mailers'] = ['default' => $mailer];

                    return $v;
                })
            ->end()
            ->children()
                ->append($this->getMailersNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Return the mailers node.
     *
     * @return ArrayNodeDefinition
     */
    protected function getMailersNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('mailers');

        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
                ->scalarNode('from_address')
                    ->info('The address message should be sent from')
                    ->defaultNull()
                ->end()
                ->scalarNode('from_name')
                    ->info('The address message should be sent from')
                    ->defaultNull()
                ->end()
            ->end();

        return $node;
    }
}
