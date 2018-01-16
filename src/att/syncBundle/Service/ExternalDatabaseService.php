<?php

namespace att\syncBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use att\ctrlaccBundle\Entity\DeviceMetadataConfiguration;
use att\syncBundle\Entity\Atexternsystem;
use att\syncBundle\Entity\ExternalDatabase;
use att\syncBundle\Entity\ExternalDatabaseType;
use att\syncBundle\Entity\ExternalSystemSequence;

class ExternalDatabaseService {

    protected $container;
    protected $em;

    /**
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function connectionRemoteDatabase(ExternalDatabase $database) {
        $connection = $this->container->get('util.database.service')->connectToDataBase(
                $database->getSystem()->getIp(), $database->getSystem()->getPort(), $database->getDatabaseName(), $database->getDatabaseUser(), $database->getDatabaseUserPassword(), $database->getType()->getDescription());
        return $connection;
    }

    public function getEventsFromRemoteDatabase(ExternalDatabase $database, $idoriginal, $date, $time, $employee, $event, $table, $next) {

        $connection = $this->connectionRemoteDatabase($database);
        $result = [];
        $sql = "SELECT " . $idoriginal . " as 'idoriginal'," . ""
                . "unix_timestamp(" . $date . ") as 'eventdate'," . ""
                . "unix_timestamp(" . $time . ") as 'eventtime'," .
                $employee . " as 'employee'," .
                $event . " as 'eventtype' "
                . "FROM " . $table . " "
                . "WHERE " . $idoriginal . " >= " . $next . ";";

        $stmt = $connection->query($sql);

        while ($row = $stmt->fetch()) {
            $result[] = $row;
        }


        return $result;
    }

    public function updateDatabaseSequence(ExternalSystemSequence $secuence, $new_last, $new_next) {

        $secuence->setLast($new_last)
                ->setNext($new_next)
                ->setLastDateConnection(new \DateTime());
        $this->em->flush($secuence);
        return $secuence;
    }

    public function syncEventsFromExternalDatabase(Atexternsystem $system, DeviceMetadataConfiguration $metadata) {
        $ctrlacc_events_result = [];
        $database = $this->em->getRepository("syncBundle:ExternalDatabase")->findOneBySystem($system);

        if ($database) {
            $secuence = $this->em->getRepository("syncBundle:ExternalSystemSequence")->findOneBySystem($system);

            if ($secuence) {
                /* Busca eventos en la base de datos externa */
                $events_from_remote_database = $this->getEventsFromRemoteDatabase(
                        $database, $metadata->getIdAttribute(), $metadata->getEventdateAttribute(), $metadata->getEventimeAttribute(), $metadata->getEmployeeAttribute(), $metadata->getEventAttribute(), $metadata->getTableOrMethod(), $secuence->getNext());

                /* Si hay eventos de la base externa los proceso como objetos evento del módulo ctrlacc y los inserto */

                if (count($events_from_remote_database) > 0) {
                    $ctrlacc_events_result = $this->container->get('sync.remote.service')->processRemoteEventsToCtrlAccEvent($events_from_remote_database, $system, $metadata->getInEventValue(), $metadata->getOutEventValue());

                    return [
                        'status' => true,
                        'events_from_remote_database' => $events_from_remote_database,
                        'ctrlacc_event_persisted' => $ctrlacc_events_result['ctrlacc_event_persisted'],
                        'ctrlacc_event_error_persisting'=> $ctrlacc_events_result['ctrlacc_event_error_persisting'],
                        'ctrlacc_last_id_by_device_persisted' => $ctrlacc_events_result['ctrlacc_last_id_by_device_persisted'],
                        'sequence' => $secuence
                    ];
                } else {
                    return[
                        'status' => false,
                        'message' => $this->container->get('translator')->trans("There are no new events to synchronize")
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'error' => $this->container->get('translator')->trans("Can not find the sequence of the remote system")
                ];
            }
        } else {
            return [
                'status' => false,
                'error' => $this->container->get('translator')->trans("You must configure the remote DataBase")
            ];
        }
    }

}
