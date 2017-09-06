<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atdepartment
 *
 * @ORM\Table(name="emp_department")
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtdepartmentRepository")
 */
class Atdepartment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * 
     * @var \att\employeeBundle\Entity\Atbusiness
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atbusiness", cascade={"persist"})
     * @ORM\JoinColumn(name="business", referencedColumnName="id", nullable=false)
     * 
     */
    private $business;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Atdepartment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }




    /**
     * Set business
     *
     * @param \att\employeeBundle\Entity\Atbusiness $business
     *
     * @return Atdepartment
     */
    public function setBusiness(\att\employeeBundle\Entity\Atbusiness $business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \att\employeeBundle\Entity\Atbusiness
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
