<?php
namespace att\appBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

class ParametersCompilerPass implements CompilerPassInterface{
    public function process(ContainerBuilder $container) {
        $em = $container->get('doctrine.orm.default_entity_manager');
        $att_max_hours_tolerance = $em->getRepository('appBundle:AttConfiguration')->findOneBy(['parameter' => 'att_max_hours_tolerance']);
        $container->setParameter('att_max_hours_tolerance', $att_max_hours_tolerance->getValue());
    }
}

/**
 * public function build(ContainerBuilder $container){
     parent::build($container);
     $container->ad
 }
 */