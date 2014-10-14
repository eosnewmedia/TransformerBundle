<?php


namespace Enm\TransformerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class BaseConfiguration
{

  protected function addEventConfiguration()
  {
    $builder = new TreeBuilder();
    $node    = $builder->root('events');

    $node->addDefaultsIfNotSet()
         ->children()
           ->arrayNode('subscribers')
             ->prototype('scalar')->end()
             ->defaultValue(array())
           ->end()
           ->arrayNode('listeners')
             ->useAttributeAsKey('name')
             ->prototype('array')
               ->children()
                ->scalarNode('event')->end()
                ->scalarNode('class')->end()
                ->scalarNode('method')->end()
                ->integerNode('priority')->defaultValue(0)->end()
               ->end()
             ->end()
             ->defaultValue(array())
           ->end()
         ->end();

    return $node;
  }
}
