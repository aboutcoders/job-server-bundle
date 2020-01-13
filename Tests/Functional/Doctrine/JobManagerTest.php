<?php

namespace Abc\JobServerBundle\Tests\Functional\Doctrine;

use Abc\Job\JobFilter;
use Abc\Job\Model\Job;
use Abc\Job\Model\JobManagerInterface;
use Abc\Job\Status;
use Abc\Job\Type;
use Abc\JobServerBundle\Tests\Functional\DatabaseTestTrait;
use Abc\JobServerBundle\Tests\Functional\KernelTestCase;

use Symfony\Component\HttpKernel\KernelInterface;

class JobManagerTest extends KernelTestCase
{
    use DatabaseTestTrait;

    /**
     * @var KernelInterface
     */
    protected static $kernel;

    /**
     * @var JobManagerInterface
     */
    private $jobManager;

    public function setUp(): void
    {
        parent::setUp();

        static::$kernel = static::createKernel();

        $this->setUpDatabase(static::$kernel);

        $this->jobManager = $this->getJobManager(static::$kernel);
    }

    public function testFindByFiltersById()
    {
        $job_A = $this->createJob('job_A', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->createJob('job_B', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_C = $this->createJob('job_C', null, Status::SCHEDULED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setIds([$job_A->getId(), $job_B->getId()]);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersByStatus()
    {
        $job_A = $this->createJob('job_A', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->createJob('job_B', null, Status::RUNNING, new \DateTime("@100"));
        $job_C = $this->createJob('job_C', null, Status::FAILED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setStatus([Status::SCHEDULED, Status::RUNNING]);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersByName()
    {
        $job_A = $this->createJob('job_A', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->createJob('job_B', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_C = $this->createJob('job_C', null, Status::SCHEDULED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setNames(['job_A', 'job_B']);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersByExternalId()
    {
        $job_A = $this->createJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->createJob('job_A', 'externalId_B', Status::SCHEDULED, new \DateTime("@100"));
        $job_C = $this->createJob('job_A', 'externalId_C', Status::SCHEDULED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setExternalIds(['externalId_A', 'externalId_B']);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersCombinesFilters()
    {
        $job_A = $this->createJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->createJob('job_B', 'externalId_B', Status::RUNNING, new \DateTime("@100"));
        $job_C = $this->createJob('job_C', 'externalId_C', Status::FAILED, new \DateTime("@100"));
        $job_D = $this->createJob('job_D', 'externalId_D', Status::RUNNING, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setIds([$job_A->getId(), $job_B->getId(), $job_C->getId()]);
        $filter->setNames(['job_A', 'job_B', 'job_C']);
        $filter->setStatus([Status::SCHEDULED, Status::RUNNING, Status::FAILED]);
        $filter->setExternalIds(['externalId_A', 'externalId_B', 'externalId_C']);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(3, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
        $this->assertContains($job_C, $jobs);
    }

    public function testFindByFiltersByLatestExternal()
    {
        $job_A = $this->createJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->createJob('job_B', 'externalId_A', Status::SCHEDULED, new \DateTime("@101"));
        $job_C = $this->createJob('job_C', 'externalId_B', Status::SCHEDULED, new \DateTime("@100"));
        $job_D = $this->createJob('job_D', 'externalId_B', Status::SCHEDULED, new \DateTime("@101"));

        $filter = new JobFilter();
        $filter->setExternalIds(['externalId_A', 'externalId_B']);
        $filter->setLatest(true);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_B, $jobs);
        $this->assertContains($job_D, $jobs);
    }

    private function createJob(string $name, ?string $externalId, ?string $status, ?\DateTime $createdAt): Job
    {
        $job = new Job();
        $job->setType(Type::JOB());
        $job->setName($name);
        $job->setStatus($status);
        $job->setExternalId($externalId);
        $job->setCreatedAt($createdAt);

        $this->jobManager->save($job);

        return $job;
    }

    private function getJobManager(KernelInterface $kernel): JobManagerInterface
    {
        return $kernel->getContainer()->get('abc.job.job_manager');
    }
}
