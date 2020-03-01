<?php

namespace Abc\JobWorkerBundle\Tests\Functional\Command;

use Abc\JobServerBundle\Tests\Functional\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class ProcessReplyCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $timestamp = strtotime('yesterday');
        $timeLimit = new \DateTime("@$timestamp");

        $command = $application->find('abc:process:reply');

        $input = [
            'command' => $command->getName(),

            // pass arguments to the helper
            'queues' => ['queueA'],

            // limits extension
            '--message-limit' => 1,
            '--time-limit' => $timeLimit->format('Y-m-d H:i:s'),
            '--memory-limit' => 1024,
            '--niceness' => 1,

            // queue consumer options
            '--receive-timeout' => 10,

            // logger extension
            '--logger' => 'stdout',
            '--vvv',
        ];
        $options = ['verbosity' => OutputInterface::VERBOSITY_DEBUG];

        $commandTester = new CommandTester($command);
        $commandTester->execute($input, $options);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Consumption has started', $output);
    }
}
