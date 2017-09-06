<?php
namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class CertificateStudeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                
                ->add('certificate', new AtcertificateType($this->em),[
                    'label' => false,
                    'data_class' => '\att\attBundle\Entity\Atcertificate', 
                    ])
                
                ->add('dateexam', DateType::class, [
                    'label'   => false,
                    'error_bubbling' => true,
                    'widget'  => 'single_text',
                    'html5'   => false,
                    'attr'   => [
                        'class' => 'datepicker',
                        ]
                    ])
                
                ->add('institute', TextType::class, [
                    'label' => false,
                    'error_bubbling' => true,
                    ])

                ->add('subject', TextType::class, [
                    'label' => false,
                    'error_bubbling' => true,
                    ])
    
                ;
        }
    
}