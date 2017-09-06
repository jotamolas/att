<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Valid;

class AllEmployeeType extends AbstractType {

    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {



        $builder
                ->add('employees', \Symfony\Component\Form\Extension\Core\Type\CollectionType::class, [
                    'entry_type' => new EmployeeType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'error_bubbling' => NULL,
                    'data' => $this->data,
                    //'cascade_validation' => true
                    'constraints' => [
                        new Valid()
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => NULL
        ));
    }

}
