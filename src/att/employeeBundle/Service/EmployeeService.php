<?php

namespace att\employeeBundle\Service;

use att\employeeBundle\Entity\Atemployee;
use DataDog\PagerBundle\Pagination;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class EmployeeService {

    protected $em;
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->container = $container;
    }

    public function filters(QueryBuilder $qb, $key, $val) {

        switch ($key) {

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

            case 'e.sex':
                if ($val) {
                    $qb->andWhere($qb->expr()->like('e.sex', ':sex'));
                    $qb->setParameter('sex', "%$val%");
                }
                break;

            default:

                throw new \Exception("filter not allowed");
        }
    }

    public function pagination(Request $request) {

        $dql = $this->em->getRepository('employeeBundle:Atemployee')->createQueryBuilder('e')

        ;

        $options = [
            'sorters' => ['e.surname' => 'ASC'],
            'applyFilter' => [$this, 'filters'],
        ];

        $gender = [
            Pagination::$filterAny => 'Any',
            'M' => 'M',
            'F' => 'F'
        ];
        $paginator = new Pagination($dql, $request, $options);
        return [
            "paginator" => $paginator,
            "gender" => $gender
        ];
    }

    public function persistEmployee(Atemployee $employee) {
        $this->em->persist($employee);
        $this->em->flush();
    }

    public function persistEmployees($employees) {

        $batchSize = 20;
        $i = 0;
        shuffle($employees);
        $created_employees = [];
        $errors = [];
        foreach ($employees as $employee) {

            $validateErrors = $this->container->get('validator')->validate($employee);

            if (count($validateErrors) === 0) {
                $this->em->persist($employee);
                if (($i % $batchSize) === 0) {
                    $this->em->flush(); // Executes all updates.
                    $this->em->clear(); // Detaches all objects from Doctrine!
                    $created_employees[] = $employee;
                }
                ++$i;
            } else {
                $errors[] = $validateErrors;
            }
        }
        $this->em->flush();


        return [
            'employees' => $created_employees,
            'constraint_errors' => $errors];
    }

    public function updateEmployees($employees) {

        $batchSize = 20;
        $i = 1;
        $errors = [];
        $constraint_errors= [];
        foreach ($employees as $emp) {
            $validateErrors = $this->container->get('validator')->validate($emp);
            $i ++;
            if (count($validateErrors) === 0) {
                try {
                    if (($i % $batchSize) == 0) {
                        $this->em->flush();
                        $this->em->clear();
                    }
                    $this->em->flush();
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                    $i--;
                }
            }else{
                $constraint_errors[]=$constraint_errors;
            }
        }

        return [
            'employees' => $employees,
            'exception_errors' => $errors,
            'constraint_errors' => $constraint_errors
        ];
    }

    public function validateFileHeader($header) {
        
        $result = array_diff(['dni', 'name', 'surname', 'genre'], $header);

        if (count($result) > 0) {
            $message = "The file not contain this fields : ";
            foreach ($result as $field) {
                $message .= " " . $field . " ";
            }
            return[
                'error' => true,
                'message' => $message
            ];
        }

        return ['error' => false];
    }

    /*     * *
     * Para la importacion se recibe un array iterable con sus keys obligatorias como
     * dni nombre apellido sexo
     * 
     */

    public function importProcess($employees) {
        
        $update_employees = [];
        $new_employees = [];
        $setting_errors = [];
        
        foreach ($employees as $employee) {
            
            $exist_employee = $this->em->getRepository("employeeBundle:Atemployee")->findOneByDni($employee['dni']);
            
            if ($exist_employee) {
                $result = $this->setUpdateEmployee($exist_employee, $employee);
                $update_employees[] = $result['employee'];
                !$result['field_errors'] ?  NULL : $setting_errors[] = $result['field_errors'] ;
               
            } else {
                $result = $this->setNewEmployee(
                        $employee['name'], $employee['surname'], $employee['dni'], array_key_exists('birthday', $employee) ? $employee['birthday'] : NULL, array_key_exists('genre', $employee) ? $employee['genre'] : NULL, array_key_exists('address', $employee) ? $employee['address'] : NULL, array_key_exists('phone', $employee) ? $employee['phone'] : NULL, array_key_exists('mobil', $employee) ? $employee['mobil'] : NULL, array_key_exists('city', $employee) ? $employee['city'] : NULL, array_key_exists('state', $employee) ? $employee['state'] : NULL
                );

                $new_employees[] = $result['employee'];
                !$result['field_errors'] ?  NULL : $setting_errors[] = $result['field_errors'] ;
                
            }
        }

        $result_update = $this->updateEmployees($update_employees);
        $result_new = $this->persistEmployees($new_employees);


        return[
            'error_setting_entity' => $setting_errors,
            'error_contraint_violation' => array_merge($result_new['constraint_errors'], $result_update['constraint_errors']),
            'error_exceptions' => $result_update['exception_errors'],
            'new_employees' => $result_new['employees'],
            'updated_employees' => $result_update['employees']
                ];
    }

    public function setNewEmployee($name, $lastname, $dni, $birthday = null, $sex = null, $address = null, $phone = null, $celPhone = null, $city = null, $state = NULL) {
        
        $employee = new Atemployee();
        
        $field_errors = null;
        
        $employee->setAddress($address)
                ->setSurname($lastname)
                ->setName($name)
                ->setCelphone($celPhone)
                ->setPhone($phone)
                ->setDni($dni)
                ->setSex($sex)
                ->setCity($city)
                ->setState($state);

        if ($birthday) {
            $date = date_create_from_format('Ymd', $birthday);
            $date ? $employee->setBirthday($date) : $field_errors = 'Bithday error, date format must be Ymd, for Employee with Person ID:  '.$employee->getDni();
        }

        return [
            'employee' => $employee,
            
            'field_errors' => $field_errors
        ];
    }

    public function setUpdateEmployee($exist_employee, $employee) {

        $field_errors = null;

        ($employee['name'] != $exist_employee->getName() ? $exist_employee->setName($employee['name']) : NULL );
        ($employee['surname'] != $exist_employee->getSurname() ? $exist_employee->setSurname($employee['surname']) : NULL );
        ($employee['genre'] != $exist_employee->getSex() ? $exist_employee->setSex($employee['genre']) : NULL);

        array_key_exists('address', $employee) ? $exist_employee->setAddress($employee['address']) : NULL;
        array_key_exists('city', $employee) ? $exist_employee->setCity($employee['city']) : NULL;
        array_key_exists('state', $employee) ? $exist_employee->setState($employee['state']) : NULL;

        if (array_key_exists('birthday', $employee)) {
            $date = date_create_from_format('Ymd', $employee['birthday']);
            $date ? $exist_employee->setBirthday($date) : $field_errors = 'Bithday error, date format must be Ymd, for Employee with Person ID: '.$exist_employee->getDni();
        }

        return [
            'employee' => $employee,
            'field_errors' => $field_errors
        ];
    }

}
