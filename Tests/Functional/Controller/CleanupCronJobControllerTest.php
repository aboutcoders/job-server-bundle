<?php

namespace Abc\JobServerBundle\Tests\Functional\Controller;

use Abc\JobServerBundle\Controller\CleanupCronJobController;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class CleanupCronJobControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get(CleanupCronJobController::class);
        $this->assertInstanceOf(CleanupCronJobController::class, $consumer);
    }
}
