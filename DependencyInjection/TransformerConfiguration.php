<?php
/**
 * TransformerConfiguration.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\DependencyInjection;

use ENM\TransformerBundle\ConfigurationStructure\StringValidationEnum;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
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
    $rootNode    = $treeBuilder->root('enm_transformer');

    $rootNode
        ->useAttributeAsKey('name')
        ->prototype('array')
          ->children()
            ->arrayNode('events')
              ->addDefaultsIfNotSet()
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
              ->end()
            ->end()
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }
}