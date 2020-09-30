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
        $job_A = $this->saveJob('job_A', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->saveJob('job_B', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_C = $this->saveJob('job_C', null, Status::SCHEDULED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setIds([$job_A->getId(), $job_B->getId()]);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersByStatus()
    {
        $job_A = $this->saveJob('job_A', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->saveJob('job_B', null, Status::RUNNING, new \DateTime("@100"));
        $job_C = $this->saveJob('job_C', null, Status::FAILED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setStatus([Status::SCHEDULED, Status::RUNNING]);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersByName()
    {
        $job_A = $this->saveJob('job_A', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->saveJob('job_B', null, Status::SCHEDULED, new \DateTime("@100"));
        $job_C = $this->saveJob('job_C', null, Status::SCHEDULED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setNames(['job_A', 'job_B']);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersByExternalId()
    {
        $job_A = $this->saveJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->saveJob('job_A', 'externalId_B', Status::SCHEDULED, new \DateTime("@100"));
        $job_C = $this->saveJob('job_A', 'externalId_C', Status::SCHEDULED, new \DateTime("@100"));

        $filter = new JobFilter();
        $filter->setExternalIds(['externalId_A', 'externalId_B']);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_A, $jobs);
        $this->assertContains($job_B, $jobs);
    }

    public function testFindByFiltersCombinesFilters()
    {
        $job_A = $this->saveJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->saveJob('job_B', 'externalId_B', Status::RUNNING, new \DateTime("@100"));
        $job_C = $this->saveJob('job_C', 'externalId_C', Status::FAILED, new \DateTime("@100"));
        $job_D = $this->saveJob('job_D', 'externalId_D', Status::RUNNING, new \DateTime("@100"));

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
        $job_A = $this->saveJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $job_B = $this->saveJob('job_B', 'externalId_A', Status::SCHEDULED, new \DateTime("@101"));
        $job_C = $this->saveJob('job_C', 'externalId_B', Status::SCHEDULED, new \DateTime("@100"));
        $job_D = $this->saveJob('job_D', 'externalId_B', Status::SCHEDULED, new \DateTime("@101"));

        $filter = new JobFilter();
        $filter->setExternalIds(['externalId_A', 'externalId_B']);
        $filter->setLatest(true);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertCount(2, $jobs);
        $this->assertContains($job_B, $jobs);
        $this->assertContains($job_D, $jobs);
    }

    public function testFindByWithNonExistingExternalId()
    {
        $filter = new JobFilter();
        $filter->setExternalIds(['externalId_A']);
        $filter->setLatest(true);

        $jobs = $this->jobManager->findBy($filter);

        $this->assertEmpty($jobs);
    }

    /**
     * @dataProvider provideConcurrentExistsData
     */
    public function testExistsConcurrentWithConcurrentExists(array $savedJobArray, array $givenJobArray)
    {
        $this->saveJob(
            $savedJobArray['name'],
            $savedJobArray['externalId'] ?? null,
            $savedJobArray['status'],
            new \DateTime("@100"),
            $savedJobArray['input'] ?? null
        );

        $job = \Abc\Job\Job::fromArray($givenJobArray);

        $this->assertTrue($this->jobManager->existsConcurrent($job));
    }

    /**
     * @dataProvider provideNoConcurrentExistsData
     */
    public function testExistsConcurrentWithNoConcurrentExists(array $savedJobArray, array $givenJobArray)
    {
        $this->saveJob(
            $savedJobArray['name'],
            $savedJobArray['externalId'] ?? null,
            $savedJobArray['status'],
            new \DateTime("@100"),
            $savedJobArray['input'] ?? null
        );

        $job = \Abc\Job\Job::fromArray($givenJobArray);

        $this->assertFalse($this->jobManager->existsConcurrent($job));
    }

    public function testExistsConcurrentWithNoJobs()
    {
        $job = \Abc\Job\Job::fromArray(
            [
                'name' => 'SomeName',
                'type' => Type::JOB(),
            ]
        );

        $this->assertFalse($this->jobManager->existsConcurrent($job));
    }

    public function testExistsConcurrentWithOlderConcurrentJob()
    {
        $name = 'someName';

        $this->saveJob($name, null, Status::RUNNING, new \DateTime("@100"));
        $this->saveJob($name, null, Status::CANCELLED, new \DateTime("@200"));


        $job = \Abc\Job\Job::fromArray(
            [
                'name' => $name,
                'type' => Type::JOB(),
            ]
        );

        $this->assertFalse($this->jobManager->existsConcurrent($job));
    }

    public function testDeleteAll()
    {
        $this->saveJob('job_A', 'externalId_A', Status::SCHEDULED, new \DateTime("@100"));
        $this->saveJob('job_B', 'externalId_A', Status::SCHEDULED, new \DateTime("@101"));
        $this->saveJob('job_C', 'externalId_B', Status::SCHEDULED, new \DateTime("@100"));
        $this->saveJob('job_D', 'externalId_B', Status::SCHEDULED, new \DateTime("@101"));

        $this->assertEquals(4, $this->jobManager->deleteAll());
        $this->assertEmpty($this->jobManager->findBy());
    }

    private function saveJob(
        string $name,
        ?string $externalId,
        ?string $status,
        ?\DateTime $createdAt,
        string $input = null
    ): Job {
        $job = new Job();
        $job->setType(Type::JOB());
        $job->setName($name);
        $job->setStatus($status);
        $job->setInput($input);
        $job->setExternalId($externalId);
        $job->setCreatedAt($createdAt);

        $this->jobManager->save($job);

        return $job;
    }

    private function getJobManager(KernelInterface $kernel): JobManagerInterface
    {
        return $kernel->getContainer()
            ->get('abc.job.job_manager');
    }

    public function provideConcurrentExistsData()
    {
        return [
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::SCHEDULED],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::WAITING],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::RUNNING],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'someInput', 'status' => Status::RUNNING],
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'someInput']
            ],
            [
                [
                    'type' => Type::JOB(),
                    'name' => 'JobA',
                    'externalId' => 'someExternalId',
                    'status' => Status::RUNNING
                ],
                ['type' => Type::JOB(), 'name' => 'JobA', 'externalId' => 'someExternalId']
            ]
            ,
            [
                [
                    'type' => Type::JOB(),
                    'name' => 'JobA',
                    'input' => 'someInput',
                    'externalId' => 'someExternalId',
                    'status' => Status::RUNNING
                ],
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'someInput', 'externalId' => 'someExternalId']
            ]
        ];
    }

    public function provideNoConcurrentExistsData()
    {
        return [
            # name does not match
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::SCHEDULED],
                ['type' => Type::JOB(), 'name' => 'JobB']
            ],
            # status does not match
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::CANCELLED],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::COMPLETE],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            # externalId does not match
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'externalId' => 'someExternalId', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'externalId' => 'someExternalId', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA', 'externalId' => 'anotherExternalId']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA', 'externalId' => 'anotherExternalId']
            ],
            # input does not match
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'someInput', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'someInput', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'differentInput']
            ],
            [
                ['type' => Type::JOB(), 'name' => 'JobA', 'status' => Status::FAILED],
                ['type' => Type::JOB(), 'name' => 'JobA', 'input' => 'someInput']
            ]
        ];
    }
}
