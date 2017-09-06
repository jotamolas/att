<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atemployeestate
 *
 * @ORM\Table(name="emp_employee_status")
 * @ORM\Entity
 */
class Atemployeestatus
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=45, nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    
    /**
     * Set description
     *
     * @param string $description
     * @return Atemployeestate
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __toString() {
        return "Id:".$this->getId()." Description:". $this->getDescription();
    }
}
