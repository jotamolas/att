<?php

namespace att\employeeBundle\Entity;
use att\employeeBundle\Entity\Atcontract;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Groups;



/**
 * Atbusiness
 * @ORM\Table(name="emp_business",
 *      indexes={@ORM\Index(name="business_company_idx", columns={"company"})}
 *      )
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtbusinessRepository")
 */
class Atbusiness
{
    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="string", length=50, nullable=false)
     * 
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="state", type="string", length=50, nullable=false)
     */
    private $state;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=50, nullable=false)
     */
    private $country;
    
    /**
     * @var string
     * @ORM\Column(name="obs", type="text", nullable=true)
     */
    private $obs;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\employeeBundle\Entity\Atcompany
     * 
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atcompany",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company", referencedColumnName="id")
     * })
     * 
     * @Type("att\employeeBundle\Entity\Atcompany")
     * @Groups({"getCompany"})
     */
    private $company;

    /**
     * 
     * @ORM\OneToMany(targetEntity="\att\employeeBundle\Entity\Atcontract", mappedBy="business",cascade={"persist", "remove"}, orphanRemoval=TRUE)
     * @Type("ArrayCollection<att\employeeBundle\Entity\Atcontract>")
     * @Groups({"getContracts"})
     */
    private $contracts;
    
    public function __construct() {
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set desc
     *
     * @param string $description
     * @return Atbusiness
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
     * Set state
     *
     * @param string $state
     * @return Atbusiness
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Atbusiness
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
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
     * Set company
     *
     * @param \att\employeeBundle\Entity\Atcompany $company
     * @return Atbusiness
     */
    public function setCompany(\att\employeeBundle\Entity\Atcompany $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \att\employeeBundle\Entity\Atcompany 
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    
    
    public function getContracts()
    {
        if($this->contracts){
            return $this->contracts->toArray();
        }else{
            return FALSE;
        }
    }

    public function addcontract(Atcontract $contract)
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setBusiness($this);
        }

        return $this;
    }

    public function removecontract(Atcontract $contract)
    {
        if ($this->contracts->contains($contract)) {
            $this->contracts->removeElement($contract);
            $contract->setBusiness(null);
        }

        return $this;
    }

    public function getEmployees()
    {
        return array_map(
            function ($contract) {
                return $contract->getEmployee();
            },
            $this->contract->toArray()
        );
    }

    /**
     * Set obs
     *
     * @param string $obs
     * @return Atbusiness
     */
    public function setObs($obs)
    {
        $this->obs = $obs;

        return $this;
    }

    /**
     * Get obs
     *
     * @return string 
     */
    public function getObs()
    {
        return $this->obs;
    }
    
    
    public function __toString() {
        
        if(!$this->getCompany()){
            return "Business: ".$this->getDescription().""
                ." | Company: -".""
                ." | State: ".$this->getState().""
                ." | Country: ".$this->getCountry();
        }else{
          return "Business: ".$this->getDescription().""
                ." | Company: ".$this->getCompany()->getDescription() .""
                ." | State: ".$this->getState().""
                ." | Country: ".$this->getCountry();  
        }
        
        
    }
}
