<?php

namespace att\attBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use att\attBundle\DependencyInjection\RegisterCertificateStateWorkflowCompilerPass;

class attBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new RegisterCertificateStateWorkflowCompilerPass());
    }
}
