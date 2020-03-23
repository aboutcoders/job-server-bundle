<?php

namespace Abc\JobServerBundle\Tests\Functional\Command;

use Abc\Job\Broker\Route;
use Abc\Job\Broker\RouteRegistryInterface;
use Abc\JobServerBundle\Tests\Functional\DatabaseTestTrait;
use Abc\JobServerBundle\Tests\Functional\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class SetupBrokerCommandTest extends KernelTestCase
{
    use DatabaseTestTrait;


    public function testExecute()
    {
        $kernel = static::createKernel();

        $this->setUpDatabase($kernel);

        $this->getRouteRegistry($kernel)->add(new Route('jobName', 'someQueue', 'someReplyTo'));

        $application = new Application($kernel);

        $command = $application->find('abc:broker:setup');

        $input = [
            'command' => $command->getName(),
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];

        $commandTester = new CommandTester($command);
        $commandTester->execute($input, $options);

        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertStringContainsString('someQueue', $commandTester->getDisplay());
        $this->assertStringContainsString('someReplyTo', $commandTester->getDisplay());
    }

    public function testExecuteWithoutRoutes()
    {
        $kernel = static::createKernel();

        $this->setUpDatabase($kernel);

        $application = new Application($kernel);

        $command = $application->find('abc:broker:setup');

        $input = [
            'command' => $command->getName(),
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];

        $this->expectException(\LogicException::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute($input, $options);
    }

    private function getRouteRegistry(KernelInterface $kernel): RouteRegistryInterface
    {
        return $kernel->getContainer()
            ->get('abc.job.route_registry')
            ;
    }
}
