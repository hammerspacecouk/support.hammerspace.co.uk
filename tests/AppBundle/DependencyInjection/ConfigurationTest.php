<?php

declare(strict_types = 1);
namespace Tests\AppBundle\DependencyInjection;

use AppBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigTreeBuilder()
    {
        $configuration = new Configuration();
        $expectedTreeBuilder = new TreeBuilder();
        $expectedTreeBuilder->root('app');
        $this->assertEquals($expectedTreeBuilder, $configuration->getConfigTreeBuilder());
    }
}
