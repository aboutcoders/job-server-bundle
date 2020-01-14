<?php

namespace Abc\JobServerBundle\Tests\Functional\Controller;

use Abc\JobServerBundle\Controller\CronJobController;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class CronJobControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get(CronJobController::class);
        $this->assertInstanceOf(CronJobController::class, $consumer);
    }
}
