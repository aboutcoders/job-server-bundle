<?php

namespace Abc\JobServerBundle\Tests\Functional\Controller;

use Abc\JobServerBundle\Controller\JobController;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class JobControllerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get(JobController::class);
        $this->assertInstanceOf(JobController::class, $consumer);
    }
}
