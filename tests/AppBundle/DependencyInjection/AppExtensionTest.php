<?php

declare(strict_types = 1);
namespace Tests\AppBundle\DependencyInjection;

use AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AppExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithoutErrors()
    {
        $containerBuilder = new ContainerBuilder();
        $extension = new AppExtension();
        $extension->load([], $containerBuilder);
    }
}
