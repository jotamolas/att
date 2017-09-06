<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractEmployeeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('employee', 'autocomplete_entity', [
                    'invalid_message' => "Not found employee with this Last Name",
                    'class' => 'att\employeeBundle\Entity\Atemployee',
                    'update_route' => 'employee_autocomplete_ajax',
                    'label' => 'Employee',
                    'error_bubbling' => TRUE,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank()
                    ]
                ])

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atcontract',
            'validation_groups' => ['StepForm1'],
        ));
    }

}
