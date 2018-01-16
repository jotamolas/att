<?php


namespace att\utilBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DatabaseService {
    
    
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container ;
    }

    public function connectToDataBase($hostname, $port, $dbname, $username, $password, $database) {

        /** @var \Doctrine\Bundle\DoctrineBundle\ConnectionFactory $connectionFactory */
        $connectionFactory = $this->container->get('doctrine.dbal.connection_factory');

        switch ($database) {
            case 'MySql':
                $string = "mysql:host=$hostname;dbname=$dbname";
                break;
        }

        $connection = $connectionFactory->createConnection(
                [
                    'pdo' => new \PDO($string, $username, $password)
                ]
        );

        return $connection;
    }
    
    
}
