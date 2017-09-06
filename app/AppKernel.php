    <?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(           
            
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new BeSimple\SoapBundle\BeSimpleSoapBundle(),
            new Gmorel\StateWorkflowBundle\GmorelStateWorkflowBundle(),
            new DataDog\PagerBundle\DataDogPagerBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new att\syncBundle\syncBundle(),
            new att\ctrlaccBundle\ctrlaccBundle(),
            new att\attBundle\attBundle(),
            new att\employeeBundle\employeeBundle(),
            new att\certificateBundle\certificateBundle(),
            new att\medicalsrvBundle\medicalsrvBundle(),
            new att\utilBundle\utilBundle(),
            new att\appBundle\appBundle(),
            new att\webBundle\webBundle(),
            new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
