<?php



namespace att\employeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of SynccontractCommand
 *
 * @author JotaMolas
 */
class SyncContractDownAxtonCommand extends ContainerAwareCommand{
    
    protected function configure() {
        $this->setName('employee:contract:down:sync:axton')
              ->setDescription('Sync Contract of Employees from Axton WSDL');                      
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $logger = $this->getContainer()->get('monolog.logger.employee');
        $updated=   $this->getContainer()->get('employee.contract.service')->syncInactiveFromAxton();
        
        
        $output->writeln("==================================================");
        $output->writeln("Contract Sync From External System Axton");
        $output->writeln("==================================================");
        $output->writeln("==================================================");

        $output->writeln(count($updated)." Contracts Updated");
               
        $output->writeln("==================================================");
        
        $logger->info("Contracts Active Updated: ".count($updated)); 
    }
    
    
}
