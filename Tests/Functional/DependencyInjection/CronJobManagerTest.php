<?php

namespace Abc\JobServerBundle\Tests\Functional\DependencyInjection;

use Abc\Job\CronJobManager;
use Abc\Job\Symfony\DiUtils;
use Abc\JobServerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class CronJobManagerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $diUtils = new DiUtils();

        self::bootKernel();

        $manager = self::$container->get($diUtils->format('cronjob_manager'));
        $this->assertInstanceOf(CronJobManager::class, $manager);
    }
}
