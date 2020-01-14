<?php

namespace Abc\JobServerBundle\Tests\Functional\Controller;


use Abc\JobServerBundle\Controller\RouteController;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class RouteControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get(RouteController::class);
        $this->assertInstanceOf(RouteController::class, $consumer);
    }
}
