<?php

namespace Abc\JobServerBundle\Tests\Functional\Controller;

use Abc\JobServerBundle\Controller\CleanupJobController;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class CleanupJobControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get(CleanupJobController::class);
        $this->assertInstanceOf(CleanupJobController::class, $consumer);
    }
}
