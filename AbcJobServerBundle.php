<?php

namespace Abc\JobServerBundle;

use Abc\JobServerBundle\DependencyInjection\Compiler\BuildJobProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AbcJobServerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new BuildJobProviderPass());
    }
}
