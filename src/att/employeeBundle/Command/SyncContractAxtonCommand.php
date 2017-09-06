<?php
namespace att\employeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of SynccontractCommand
 *
 * @author JotaMolas
 */
class SyncContractAxtonCommand extends ContainerAwareCommand{
    
    
    protected function configure() {
        $this->setName('employee:contract:sync:axton')
              ->setDescription('Sync Contract Down of Employees from Axton WSDL');                      
    }
    
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $logger = $this->getContainer()->get('monolog.logger.employee');
        $updated=   $this->getContainer()->get('employee.contract.service')->syncActiveFromAxton();
        $new = $this->getContainer()->get('employee.contract.service')->syncNewFromAxton();
        
        $output->writeln("==================================================");
        $output->writeln("Contract Sync From External System Axton");
        $output->writeln("==================================================");
        $output->writeln("==================================================");
        $output->writeln(count($new)." Contracts new Syncronized");
        $output->writeln("==================================================");
        $output->writeln(count($updated)." Contracts Updated");               
        $output->writeln("==================================================");
        
        $logger->info("Contracts New: ".count($new)." Contracts Active Updated: ".count($updated));
        
    }
    
    
}
