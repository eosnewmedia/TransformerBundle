<?php
/**
 * TransformerConfiguration.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class TransformerConfiguration implements ConfigurationInterface
{

  /**
   * Generates the configuration tree builder.
   *
   * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
   */
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder();
    $rootNode    = $treeBuilder->root('config');

    $rootNode
      ->useAttributeAsKey('name')
        ->prototype('array')
          ->children()
            ->booleanNode('complex')->defaultValue(false)->end()
            ->enumNode('type')->values(array('integer', 'float', 'string', 'bool', 'array'))->defaultValue(null)->end()
            ->scalarNode('methodClass')->defaultValue(null)->end()
            ->scalarNode('method')->defaultValue(null)->end()
            ->arrayNode('children')->prototype('variable')->end()->end()
            ->arrayNode('options')
              ->children()
                ->booleanNode('assoc')->defaultValue(true)->end()
                ->arrayNode('expected')->prototype('scalar')->end()->end()
                ->floatNode('min')->end()
                ->floatNode('max')->end()
                ->scalarNode('returnClass')->defaultValue(null)->end()
                ->booleanNode('required')->defaultValue(false)->end()
              ->end()
            ->end()
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }
}