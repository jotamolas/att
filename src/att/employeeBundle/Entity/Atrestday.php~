<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Atrestday
 *
 * @ORM\Table(name="emp_rest_day")
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtrestdayRepository")
 */
class Atrestday
{
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
   
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    
  
    
    
    /**
     * @var \Doctrine\Common\Collections\Collection|Atcontract[]
     * @ORM\ManyToMany(targetEntity="att\employeeBundle\Entity\Atcontract", mappedBy="restDays",cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(name="AtContractRestDay",
     *            joinColumns=
     *              {
     *                  @ORM\JoinColumn(name="employee_id", referencedColumnName="employee"),
     *                  @ORM\JoinColumn(name="business_id", referencedColumnName="business")
     *              },
     *            inverseJoinColumns=
     *              {
     *                  @ORM\JoinColumn(name="restday_id", referencedColumnName="id")
     *              }
     *       )
     *  
     */
    private $contracts;

    public function __construct() {
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Atrestday
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
        return ucfirst($this->description);
    }
    
    
    public function addContract(Atcontract $contract)
    {
        if ($this->contracts->contains($contract)) {
            return;
        }
        $this->contracts[] = $contract;
        
    }
    
    public function getContracts(){
        return $this->contracts;
    }


    
    public function __toString() {
        return $this->getDescription();
    }

    /**
     * Remove contract
     *
     * @param \att\employeeBundle\Entity\Atcontract $contract
     */
    public function removeContract(\att\employeeBundle\Entity\Atcontract $contract)
    {
        $this->contracts->removeElement($contract);
    }
}
