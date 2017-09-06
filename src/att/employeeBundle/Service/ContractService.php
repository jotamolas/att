<?php

namespace att\employeeBundle\Service;

use att\employeeBundle\Entity\Atcontract;
use DataDog\PagerBundle\Pagination;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class ContractService {

    protected $em;
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->container = $container;
    }

    public function filters(QueryBuilder $qb, $key, $val) {

        switch ($key) {

            case 'c.file_number':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('c.file_number', ':filenumber'));
                    $qb->setParameter('filenumber', "%$val%");
                }
                break;

            case 'e.name':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.name', ':name'));
                    $qb->setParameter('name', "%$val%");
                }
                break;

            case 'e.surname':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.surname', ':surname'));
                    $qb->setParameter('surname', "%$val%");
                }
                break;


            case 'e.dni':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.dni', ':dni'));
                    $qb->setParameter('dni', "%$val%");
                }
                break;

            case 'e.province':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.province', ':province'));
                    $qb->setParameter('province', "%$val%");
                }
                break;

            case 'b.id':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('b.id', ':ident'));
                    $qb->setParameter('ident', $val);
                }
                break;

            case 'cia.id':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('cia.id', ':id'));
                    $qb->setParameter('id', $val);
                }
                break;

            case 's.description':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('s.description', ':desc'));
                    $qb->setParameter('desc', $val);
                }
                break;

            case 'sch.description':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('sch.description', ':d'));
                    $qb->setParameter('d', $val);
                }
                break;

            case 'a.id':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('a.id', ':i'));
                    $qb->setParameter('i', $val);
                }
                break;

            default:

                throw new \Exception("filter not allowed");
        }
    }

    public function pagination(Request $request) {

        $dql = $this->em->getRepository('employeeBundle:Atcontract')->createQueryBuilder('c')
                ->addSelect('e')->addSelect('b')->addSelect('s')->addSelect('sch')->addSelect('rs')
                ->addSelect('a')->addSelect('cia')
                ->leftJoin('c.employee', 'e')
                ->leftJoin('c.business', 'b')
                ->leftJoin('c.status', 's')
                ->leftJoin("c.schema", 'sch')
                ->leftJoin('c.agreement', 'a')
                ->leftJoin('b.company', 'cia')
                ->leftJoin('c.restDays', 'rs')
        ;

        $dqlAux = $this->em->getRepository('employeeBundle:Atcontract')->createQueryBuilder('e');
        $dqlAux->select($dqlAux->expr()->countDistinct('e.id'));

        $options = [
            'applyFilter' => [$this, 'filters'],
        ];

        $status = [Pagination::$filterAny => 'Any'];
        $empStatus = $this->em->getRepository("employeeBundle:Atemployeestatus")->findAll();
        foreach ($empStatus as $s) {
            $status [$s->getDescription()] = $s->getDescription();
        }

        $schemas = [Pagination::$filterAny => 'Any'];
        $empSchemas = $this->em->getRepository("employeeBundle:Atschema")->findAll();
        foreach ($empSchemas as $sch) {
            $schemas [$sch->getDescription()] = $sch->getDescription();
        }

        $agreements = [Pagination::$filterAny => 'Any'];
        $empAgree = $this->em->getRepository("employeeBundle:Atagreement")->findAll();
        foreach ($empAgree as $agree) {
            $agreements [$agree->getId()] = $agree->getDescription() . "-" . $agree->getUnion()->getDescription();
        }

        $companies = [Pagination::$filterAny => 'Any'];
        $empCia = $this->em->getRepository("employeeBundle:Atcompany")->findAll();
        foreach ($empCia as $cia) {
            $companies [$cia->getId()] = $cia->getDescription();
        }

        $business = [Pagination::$filterAny => 'Any'];
        $empBus = $this->em->getRepository("employeeBundle:Atbusiness")->findAll();
        foreach ($empBus as $buss) {
            $business [$buss->getId()] = $buss->getCompany()->getDescription() . " | " . $buss->getDescription();
        }

        $paginator = new Pagination($dql, $request, $options, $dqlAux);


        return [
            "paginator" => $paginator,
            "status" => $status,
            'schemas' => $schemas,
            'agreements' => $agreements,
            'companies' => $companies,
            'business' => $business
        ];
    }

    public function syncNewFromAxton() {

        $news = [];

        $axton = $this->container->get('sync.axton.service')->getNewEmployeeToSync(
                $this->em->getRepository("employeeBundle:Atcontract")->findAllContractToSync());

        if (!$axton) {
            return;
        }

        foreach ($axton as $ctrc) {

            $business = $this->container->get('employee.business.service')->getBusinessByCompanyAndState($ctrc->getEmpresa(), $ctrc->getProvincia());
            $employee = $this->em->getRepository("employeeBundle:Atemployee")->findOneByDni($ctrc->getDni());

            if ($business && $employee) {

                $contract = new Atcontract();
                ($ctrc->getFechaBaja() == NULL ? $contract->setStatus($this->em->getRepository('employeeBundle:Atemployeestatus')->findOneByDescription('Active')) : $contract->setEndDate(date_create_from_format('Ymd', $ctrc->getFechaBaja()))->setStatus($this->em->getRepository('employeeBundle:Atemployeestatus')->findOneByDescription('Down')));
                $contract->setFileNumber($ctrc->getLegnumero())
                        ->setStartDate(date_create_from_format('Ymd', $ctrc->getFechaIngreso()));
                $employee->addcontract($contract);
                $business->addcontract($contract);
                $news [] = $contract;
            }

            $this->em->flush();
        }
        return $news;
    }

    public function syncActiveFromAxton() {


        $axton = $this->container->get('sync.axton.service')->getExistEmployeeToSync(
                $this->em->getRepository("employeeBundle:Atemployee")->findByContractStatus('Active'));
        return $this->processToAxtonSync($axton);
    }

    public function syncInactiveFromAxton() {


        $axton = $this->container->get('sync.axton.service')->getExistEmployeeToSync(
                $this->em->getRepository("employeeBundle:Atemployee")->findByContractStatus('Down'));
        return $this->processToAxtonSync($axton);
    }

    public function processToAxtonSync($axton) {
        $contracts = [];
        foreach ($axton as $ctrc) {
            $business = $this->container->get('employee.business.service')->getBusinessByCompanyAndState($ctrc->getEmpresa(), $ctrc->getProvincia());
            $employee = $this->em->getRepository("employeeBundle:Atemployee")->findOneByDni($ctrc->getDni());
            if ($business && $employee) {
                $contract = $this->em->getRepository('employeeBundle:Atcontract')->findOneBy(
                        [
                            'employee' => $employee,
                            'business' => $business
                ]);
                if ($contract) {
                    ($ctrc->getFechaBaja() == NULL ? $contract->setStatus($this->em->getRepository('employeeBundle:Atemployeestatus')->findOneByDescription('Active')) : $contract->setEndDate(date_create_from_format('Ymd', $ctrc->getFechaBaja()))->setStatus($this->em->getRepository('employeeBundle:Atemployeestatus')->findOneByDescription('Down')));
                    $contract->setFileNumber($ctrc->getLegnumero())
                            ->setStartDate(date_create_from_format('Ymd', $ctrc->getFechaIngreso()));
                    $contracts[] = $contract;
                }
            }
        }
        $this->updateContracts($contracts);
        return $contracts;
    }

    public function updateContracts($contracts) {

        $batchSize = 20;
        $i = 1;

        foreach ($contracts as $contract) {
            $i ++;
            if (($i % $batchSize)) {
                $this->em->flush();
                $this->em->clear();
            }
            $this->em->flush();
        }
    }

    public function importSchemaFromCCM() {

        $updatedContract = [];
        $qb = $this->em->getRepository("employeeBundle:Atcontract")->createQueryBuilder('emp');
        $qb->leftJoin('emp.business', 'b')
                ->leftJoin('emp.state', 's')
                ->where("b.state = 'Buenos Aires'")
                ->andWhere("s.description = 'Active'");
        $contracts = $qb->getQuery()->getResult();

        foreach ($contracts as $contract) {

            $esquema = $this->container->get('sync.ccmamba.service')->getEmployeeSchema($contract->getFileNumber());

            $schema = $this->em->getRepository("employeeBundle:Atschema")->findOneByDescription($esquema);
            if ($schema) {
                $contract->setSchema($schema);
                $this->em->flush($contract);
                $updatedContract [] = $contract;
            }
        }

        return $updatedContract;
    }

    public function importScheduleFromCCM() {

        $updatedContract = [];

        $qb = $this->em->getRepository("employeeBundle:Atcontract")->createQueryBuilder('emp');
        $qb->leftJoin('emp.business', 'b')
                ->leftJoin('emp.status', 's')
                ->where("b.state = 'Buenos Aires'")
                ->andWhere("s.description = 'Active'");
        $contracts = $qb->getQuery()->getResult();

        foreach ($contracts as $contract) {

            $times = $this->container->get('sync.ccmamba.service')->getEmployeeTime($contract->getEmployee()->getDni());


            if ($times) {
                $contract->setInTime($times['in'])
                        ->setOutTime($times['out']);
                $this->em->flush($contract);
                $updatedContract [] = $contract;
            }
        }

        return $updatedContract;
    }

    public function importRestDayFromCCM() {


        $qb = $this->em->getRepository("employeeBundle:Atcontract")->createQueryBuilder('emp');
        $qb->leftJoin('emp.business', 'b')
                ->leftJoin('emp.status', 's')
                ->where("b.state = 'Cordoba'")
                ->andWhere("s.description = 'Active'");
        $contracts = $qb->getQuery()->getResult();

        foreach ($contracts as $contract) {


            $restdays = $this->container->get('sync.ccm.service')->getEmployeeRestDay($contract->getEmployee()->getDni());

            foreach ($restdays as $restday) {

                if ($restday) {

                    $rd = $this->em->getRepository('employeeBundle:Atrestday')->find($restday);

                    if ($rd) {
                        $contract->addRestDay($rd);
                        $rd->addContract($contract);
                        $this->em->persist($rd);
                        $this->em->persist($contract);
                        $this->em->flush();
                        /* print "<pre>";
                          var_dump($restday);
                          print $contract->getEmployee()->getId()." | ";
                          print $contract->getEmployee()->getDni()." | ";
                          print $rd->getDescription();
                          print "</pre>"; */
                    }
                }
            }
        }
        return $contracts;
    }

    public function processRestDays(Atcontract $contract, \att\employeeBundle\Entity\Atrestday $restday) {
        $contract->addRestDay($restday);
        $restday->addContract($contract);
        $this->em->persist($restday);
        $this->em->persist($contract);
        $this->em->flush();
    }

   
}
