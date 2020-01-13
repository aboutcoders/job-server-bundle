<?php

namespace Abc\JobServerBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;

trait DatabaseTestTrait
{
    public function setUpDatabase(KernelInterface $kernel)
    {
        $kernel->boot();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $application->run(new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => '1',
            '--quiet' => '1',
        ]));
        $application->run(new ArrayInput([
            'command' => 'doctrine:database:create',
            '--no-interaction' => '1',
            '--quiet' => '1',
        ]));

        $application->run(new ArrayInput([
            'command' => 'doctrine:schema:create',
            '--no-interaction' => '1',
            '--quiet' => '1',
        ]));
    }
}
