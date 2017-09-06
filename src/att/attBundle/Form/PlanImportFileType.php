<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlanImportFileType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('file', FileType::class, [
            'label' => 'Select a File to Import',
            'error_bubbling' => true,
            'attr' => [
                'class' => 'fileInput'
            ],
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\File([
                                                                    'maxSize' => "100M",
                                                                    'mimeTypes' => [
                                                                        "text/plain",
                                                                        "text/csv"
                                                                    ]
                                                                ]),
                            ]
            
        ]);
        
    }
    
    
}

