<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atschema
 *
 * @ORM\Table(
 *      name="emp_schema"
 *      )
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtschemaRepository")
 */
class Atschema
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=45, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="hours", type="decimal", precision=3, scale=1, nullable=false)
     */
    private $hours;

    /**
     * @var integer
     *
     * @ORM\Column(name="days", type="integer", nullable=false)
     */
    private $days;
    
        
    /**
     * @var \att\employeeBundle\Entity\Atagreement
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atagreement",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agreement", referencedColumnName="id")
     * })
     * 
     * 
     */
    private $agreement;



    
    
    public function __construct() {
        $this->contract = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return Atschema
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
     * Set hours
     *
     * @param string $hours
     * @return Atschema
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return string 
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set days
     *
     * @param integer $days
     * @return Atschema
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
    }
    
    
    
    public function __toString() {
        return $this->getDescription()." |||  Days: ".$this->getDays()." x Hours: ".$this->getHours();
    }

    /**
     * Set agreement
     *
     * @param \att\employeeBundle\Entity\Atagreement $agreement
     *
     * @return Atschema
     */
    public function setAgreement(\att\employeeBundle\Entity\Atagreement $agreement = null)
    {
        $this->agreement = $agreement;

        return $this;
    }

    /**
     * Get agreement
     *
     * @return \att\employeeBundle\Entity\Atagreement
     */
    public function getAgreement()
    {
        return $this->agreement;
    }
}
