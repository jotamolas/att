<?php

namespace att\employeeBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddSchemaFieldSuscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        ];
    }

    private function addSchemaForm($form, $agreement_id) {
        $formOptions = [
            'class' => 'employeeBundle:Atschema',
            'attr' => array(
                'class' => 'schema_selector',
                'style' => 'width: 100%'
            ),
            'query_builder' => function (EntityRepository $repository) use ($agreement_id) {
                $qb = $repository->createQueryBuilder('s')
                        ->innerJoin('s.agreement', 'a')
                        ->where('a.id = :agreement')
                        ->setParameter('agreement', $agreement_id)
                ;
                return $qb;
            },
            'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank()
                ]
        ];
        
         $form->add('schema', 'genemu_jqueryselect2_entity', $formOptions);


    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $schema = $accessor->getValue($data, 'schema');
        $agreement_id = ($schema) ? $schema->getAgreement()->getId() : null;

        $this->addSchemaForm($form, $agreement_id);
    }

    public function preSubmit(FormEvent $event) {
        
        $data = $event->getData();  
        $form = $event->getForm();
        
        $agreement_id = array_key_exists('agreement', $data) ? $data['agreement'] : null;
        $this->addSchemaForm($form, $agreement_id);
    }

}
