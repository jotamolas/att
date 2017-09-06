<?php

namespace att\attBundle\DependencyInjection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
/**
 * Register all entity State
 * tagged as certificate.workflow.state
 * into your Certificate State Workflow
 *
 * @author Guillaume MOREL <github.com/gmorel>
 */
class RegisterCertificateStateWorkflowCompilerPass implements CompilerPassInterface
{
    const STATE_WORKFLOW_ID = 'wf.certificate';
    const DEFAULT_STATE_KEY = 'Presented';
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->setCertificateStateWorkflow($container);
    }
    /**
     * We instantiate our Booking State Workflow with its States
     * @param ContainerBuilder $container
     */
    private function setCertificateStateWorkflow(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::STATE_WORKFLOW_ID)) {
            throw new InvalidConfigurationException('Cant find wf.certificate service');
        }
        $definition = $container->getDefinition(self::STATE_WORKFLOW_ID);
        $certificateStateServices = $container->findTaggedServiceIds('certificate.workflow.state');
        foreach ($certificateStateServices as $serviceId => $StateService) {
            $definition->addMethodCall('addAvailableState', array(new Reference($serviceId)));
        }
        $this->setCertificateStateWorkflowDefaultState($definition);
    }
    /**
     * We need to set our Booking State Workflow default State
     * @param Definition $definition
     */
    private function setCertificateStateWorkflowDefaultState(Definition $definition)
    {
        $definition->addMethodCall(
            'setStateAsDefault',
            array(
                self::DEFAULT_STATE_KEY
            )
        );
    }
}