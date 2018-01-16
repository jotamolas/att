<?php

namespace att\attBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use att\attBundle\DependencyInjection\RegisterCertificateStateWorkflowCompilerPass;
use att\appBundle\DependencyInjection\ParametersCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
class attBundle extends Bundle
{
    public function build(ContainerBuilder $container) {
        parent::build($container);
        $container->addCompilerPass(new RegisterCertificateStateWorkflowCompilerPass());
        $container->addCompilerPass(new ParametersCompilerPass(), PassConfig::TYPE_AFTER_REMOVING);
    }
}
