<?php

namespace att\attBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use att\attBundle\Entity\Atcertificate;
use att\attBundle\Entity\Atworkflow;
use att\attBundle\Workflow\Entity\WorkFlowCertificate;
use att\employeeBundle\Entity\Atemployee;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use DataDog\PagerBundle\Pagination;

class CertificateService {

    protected $em;
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function filters(QueryBuilder $qb, $key, $val) {
        switch ($key) {

            case 'c.date':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('c.date', ':date'));
                    $qb->setParameter('date', $val);
                }
                break;

            case 'c.datefrom':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('c.datefrom', ':datefrom'));
                    $qb->setParameter('datefrom', $val);
                }
                break;

            case 'c.dateto':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('c.dateto', ':dateto'));
                    $qb->setParameter('dateto', $val);
                }
                break;

            case 'c.aprobationstate':
                if ($val) {
                    $qb->andWhere($qb->expr()->eq('c.aprobationstate', ':aprobationstate'));
                    $qb->setParameter('aprobationstate', $val);
                }
                break;

            case 'ct.description':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('ct.description', ':description'));
                    $qb->setParameter('description', $val);
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

            default:

                throw new \Exception("filter not allowed");
        }
    }

    public function getCertificateById($id) {

        $certificate = $this->em->getRepository('attBundle:Atcertificate')->findOneById($id);
        return $certificate;
    }

    public function getCertificatesById(array $ids, $employee = null) {

        $repo = $this->em->getRepository('attBundle:Atcertificate');
        $qb = $repo->createQueryBuilder('c');
        $certificates = $qb->where('c.employee = :employee')
                ->andWhere($qb->expr()->in('c.id', ':ids'))
                ->setParameter('employee', $employee)
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getResult();



        return $certificates;
    }

    public function getCertificateWithoutWorkflows(Atemployee $employee) {

        $wfs = $this->em->getRepository('attBundle:Atworkflow')
                ->findByWorkflow($this->em->getRepository('attBundle:Atworkflowtype')->find('wf.certificate'));

        $certificates = $this->em->getRepository('attBundle:Atcertificate')->findByEmployee($employee);

        foreach ($certificates as $certificate) {
            $idscertificates[] = $certificate->getId();
        }

        foreach ($wfs as $wf) {
            $idscertificatesWithWorkflow [] = $wf->getEntityid();
        }

        $results = array_diff($idscertificates, $idscertificatesWithWorkflow);

        return $results;
    }

    public function initWfService(Atworkflow $workflow) {
        $wfService = new WorkFlowCertificate($this->container->get('wf.certificate'));
        $wfService->setStatekey($workflow->getStatekey());

        return $wfService;
    }

    public function pagination(Request $request, Atemployee $employee = null) {

        $dql = $this->em->getRepository('attBundle:Atcertificate')->createQueryBuilder('c')
                ->leftJoin('c.employee', 'e')
                ->leftJoin('c.type', 'ct');

        if ($employee) {
            $dql->andWhere($dql->expr()->eq('c.employee', ':employee'));
            $dql->setParameter('employee', $employee->getId());
        }

        $options = [
            'sorters' => ['c.datefrom' => 'DESC'],
            'applyFilter' => [$this, 'filters'],
        ];


        $paginator = new Pagination($dql, $request, $options);

        return array("paginator" => $paginator);
    }

    public function persistCertificate(Atcertificate $entity) {

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function saveCertificateFiles(Atcertificate $entity) {
        /**
         * 
         *  @var Symfony\Component\HttpFoundation\File\UploadedFile $file 
         */
        //$file = new UploadedFile();
        $file = $entity->getScan();
        // genera unico nombre antes de guardarlo
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        // Move the file to the directory where file are stored            
        $filesDir = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/certificates';
        $file->move($filesDir, $fileName);
        $entity->setScan($fileName);

        return $entity;
    }

    public function validateCertificateToDeleted($entity) {

        $constraint = new \att\attBundle\Validator\Constraint\CertWfCheck;

        $errors = $this->container->get('validator')
                ->validateValue(
                $entity, $constraint);

        //print_r($errors);
        if (count($errors) != 0) {
            //$var = "errores";
            foreach ($errors as $error) {
                $errorsList[] = $error->getMessage();
            }
        } else {
            //$var =  "sin errores";
            $errorsList[] = null;
        }

        return $errorsList;
    }

}
