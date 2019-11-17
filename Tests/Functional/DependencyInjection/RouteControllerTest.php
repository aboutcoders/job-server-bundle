<?php

namespace Abc\JobServerBundle\Tests\Functional\DependencyInjection;

use Abc\Job\Controller\RouteController;
use Abc\Job\Symfony\DiUtils;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class RouteControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $diUtils = new DiUtils();

        $consumer = static::$container->get($diUtils->format('base_route_controller'));
        $this->assertInstanceOf(RouteController::class, $consumer);
    }
}
