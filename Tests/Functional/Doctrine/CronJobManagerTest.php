<?php

namespace Abc\JobServerBundle\Tests\Functional\Doctrine;

use Abc\Job\CronJobFilter;
use Abc\Job\Model\CronJob;
use Abc\Job\Model\CronJobManagerInterface;
use Abc\Job\Type;
use Abc\JobServerBundle\Tests\Functional\DatabaseTestTrait;
use Abc\JobServerBundle\Tests\Functional\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class CronJobManagerTest extends KernelTestCase
{
    use DatabaseTestTrait;

    /**
     * @var KernelInterface
     */
    protected static $kernel;

    /**
     * @var CronJobManagerInterface
     */
    private $cronJobManager;

    public function setUp(): void
    {
        parent::setUp();

        static::$kernel = static::createKernel();

        $this->setUpDatabase(static::$kernel);

        $this->cronJobManager = $this->getCronJobManager(static::$kernel);
    }

    public function testFindByFiltersById()
    {
        $cronJob_A = $this->createCronJob('cronJob_A', null);
        $cronJob_B = $this->createCronJob('cronJob_B', null);
        $cronJob_C = $this->createCronJob('cronJob_C', null);

        $filter = new CronJobFilter();
        $filter->setIds([$cronJob_A->getId(), $cronJob_B->getId()]);

        $jobs = $this->cronJobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($cronJob_A, $jobs);
        $this->assertContains($cronJob_B, $jobs);
    }

    public function testFindByFiltersByName()
    {
        $cronJob_A = $this->createCronJob('cronJob_A', null);
        $cronJob_B = $this->createCronJob('cronJob_B', null);
        $cronJob_C = $this->createCronJob('cronJob_C', null);

        $filter = new CronJobFilter();
        $filter->setNames(['cronJob_A', 'cronJob_B']);

        $jobs = $this->cronJobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($cronJob_A, $jobs);
        $this->assertContains($cronJob_B, $jobs);
    }

    public function testFindByFiltersByExternalId()
    {
        $cronJob_A = $this->createCronJob('job_A', 'externalId_A');
        $cronJob_B = $this->createCronJob('job_A', 'externalId_B');
        $cronJob_C = $this->createCronJob('job_A', 'externalId_C');

        $filter = new CronJobFilter();
        $filter->setExternalIds(['externalId_A', 'externalId_B']);

        $jobs = $this->cronJobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($cronJob_A, $jobs);
        $this->assertContains($cronJob_B, $jobs);
    }

    private function createCronJob(string $name, ?string $externalId, ?\DateTime $createdAt = null): CronJob
    {
        $job = \Abc\Job\Job::fromArray(
            [
                'type' => Type::JOB(),
                'name' => $name,
                'externalId' => $externalId
            ]
        );

        $cronJob = new CronJob('* * * * *', $job);

        $this->cronJobManager->save($cronJob);

        return $cronJob;
    }

    private function getCronJobManager(KernelInterface $kernel): CronJobManagerInterface
    {
        return $kernel->getContainer()
            ->get(CronJobManagerInterface::class);
    }
}
