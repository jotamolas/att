<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atunion
 *
 * @ORM\Table(name="emp_union")
 * @ORM\Entity
 */
class Atunion
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=50, nullable=false)
     */
    private $abbreviation;

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
     * @return Atunion
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
    
    
    /**
     * Set abbreviation
     *
     * @param string $abbreviation
     *
     * @return Atunion
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }
    
    public function __toString() {
        return $this->getDescription()." - ".$this->getAbbreviation();
    }
}
