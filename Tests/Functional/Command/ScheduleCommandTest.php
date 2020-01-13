<?php

namespace Abc\JobWorkerBundle\Tests\Functional\Command;

use Abc\JobServerBundle\Tests\Functional\DatabaseTestTrait;
use Abc\JobServerBundle\Tests\Functional\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class ScheduleCommandTest extends KernelTestCase
{
    use DatabaseTestTrait;

    public function testExecute()
    {
        $timestamp = strtotime('yesterday');
        $timeLimit = new \DateTime("@$timestamp");

        $kernel = static::createKernel();

        $this->setUpDatabase($kernel);

        $application = new Application($kernel);

        $command = $application->find('abc:schedule');

        $input = [
            'command' => $command->getName(),

            '--memory-limit' => 0,
            '--time-limit' => $timeLimit->format('Y-m-d H:i:s'),

            // logger extension
            '--logger' => 'stdout',
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];

        $commandTester = new CommandTester($command);
        $commandTester->execute($input, $options);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertContains('Iterate over schedules of provider', $output);
    }
}
