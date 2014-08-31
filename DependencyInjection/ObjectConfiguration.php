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

class ObjectConfiguration implements ConfigurationInterface
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
            ->enumNode('type')->values(array_merge(TypeEnum::toArray(), array_map('strtoupper', TypeEnum::toArray())))->defaultValue(null)->end()
            ->scalarNode('renameTo')->defaultValue(null)->end()
            ->arrayNode('children')->prototype('variable')->end()->end()
            ->arrayNode('options')
            ->addDefaultsIfNotSet()
              ->children()
                ->booleanNode('assoc')->defaultValue(true)->end()
                ->arrayNode('expected')->prototype('scalar')->end()->end()
                ->enumNode('stringValidation')->values(array_merge(StringValidationEnum::toArray(), array_map('strtoupper', StringValidationEnum::toArray())))->defaultValue(null)->end()
                ->booleanNode('strongValidation')->defaultValue(false)->end()
                ->floatNode('min')->defaultValue(null)->end()
                ->floatNode('max')->defaultValue(null)->end()
                ->scalarNode('returnClass')->defaultValue(null)->end()
                ->variableNode('defaultValue')->defaultValue(null)->end()
                ->scalarNode('regex')->defaultValue(null)->end()
                ->arrayNode('date')
                  ->addDefaultsIfNotSet()
                  ->children()
                    ->variableNode('format')->defaultValue('Y-m-d')->end()
                    ->booleanNode('convertToObject')->defaultValue(false)->end()
                    ->scalarNode('convertToFormat')->defaultValue(null)->end()
                  ->end()
                ->end()
                ->booleanNode('required')->defaultValue(false)->end()
                ->arrayNode('requiredIfNotAvailable')
                ->addDefaultsIfNotSet()
                  ->children()
                    ->arrayNode('and')
                      ->prototype('scalar')->end()
                      ->defaultValue(array())
                    ->end()
                    ->arrayNode('or')
                      ->prototype('scalar')->end()
                      ->defaultValue(array())
                    ->end()
                  ->end()
                ->end()
                ->arrayNode('requiredIfAvailable')
                  ->addDefaultsIfNotSet()
                  ->children()
                    ->arrayNode('and')
                      ->prototype('scalar')->end()
                      ->defaultValue(array())
                    ->end()
                    ->arrayNode('or')
                      ->prototype('scalar')->end()
                      ->defaultValue(array())
                    ->end()
                  ->end()
                ->end()
                ->arrayNode('forbiddenIfNotAvailable')
                  ->prototype('scalar')->end()
                  ->defaultValue(array())
                ->end()
                ->arrayNode('forbiddenIfAvailable')
                  ->prototype('scalar')->end()
                  ->defaultValue(array())
                ->end()
                ->arrayNode('length')
                  ->addDefaultsIfNotSet()
                  ->children()
                    ->floatNode('min')->defaultValue(null)->end()
                    ->floatNode('max')->defaultValue(null)->end()
                  ->end()
                ->end()
                ->arrayNode('individual')->prototype('variable')->end()->end()
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
          ->end()
        ->end()
      ->end();

    return $treeBuilder;
  }
}