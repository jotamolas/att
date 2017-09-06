<?php

namespace att\employeeBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class AddAgreementFieldSuscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        ];
    }

    private function addAgreementForm($form, $agreement = null) {
        $formOptions = array(
            'class' => 'employeeBundle:Atagreement',
            'placeholder' => 'Select one choice...',
            'mapped' => false,
            'attr' => array(
                'class' => 'agreement_selector',
                'style' => 'width: 100%'
            ),
            'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank()
                ]
        );

        if ($agreement) {
            $formOptions['data'] = $agreement;
        }

        $form->add('agreement', 'genemu_jqueryselect2_entity', $formOptions);
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $schema = $accessor->getValue($data, 'schema');
        $agreement = ($schema) ? $agreement->getSchema() : null;

        $this->addAgreementForm($form, $agreement);
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $this->addAgreementForm($form);
    }

}
