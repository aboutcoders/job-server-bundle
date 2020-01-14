<?php

namespace Abc\JobServerBundle\DependencyInjection;

use Abc\Job\Symfony\MissingComponentFactory;
use Abc\Scheduler\Scheduler;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        if (method_exists(TreeBuilder::class, 'getRootNode')) {
            $tb = new TreeBuilder('abc_job_server');
            $rootNode = $tb->getRootNode();
        } else {
            $tb = new TreeBuilder();
            $rootNode = $tb->root('abc_job_server');
        }

        $rootNode
            ->children()
                ->scalarNode('transport')->defaultValue('default')->end()
                ->append($this->getSchedulerConfiguration())
                ->append($this->getEndpointsConfiguration())
            ->end();

        return $tb;
    }

    private function getSchedulerConfiguration(): ArrayNodeDefinition
    {
        if (false === class_exists(Scheduler::class)) {
            return MissingComponentFactory::getConfiguration('cronjob', ['abc/scheduler-bundle']);
        }

        return (new ArrayNodeDefinition('cronjob'))
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ;
    }

    private function getEndpointsConfiguration(): ArrayNodeDefinition
    {
        return (new ArrayNodeDefinition('cleanup'))
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ;
    }
}
