<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Atplan
 * 
 * @ORM\Table(name="att_plan", indexes={@ORM\Index(name="IXFK_Plan_Empleado", columns={"employee"}), @ORM\Index(name="IXFK_Plan_StatePlan", columns={"statePlan"})})
 * @ORM\Entity(repositoryClass="att\attBundle\Repository\AtplanRepository")
 * @UniqueEntity(
 *      fields={"employee", "date"},
 *      message="The employee already has a plan allotted for this date",
 *      errorPath="date"
 * )
 */
class Atplan
{
    /**
     * @var \DateTime
     * @Assert\NotNull()
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     * @Assert\NotNull()
     * @ORM\Column(name="inPlan", type="datetime", nullable=false)
     */
    private $inplan;

    /**
     * @var \DateTime
     * @Assert\NotNull()
     * @ORM\Column(name="outPlan", type="datetime", nullable=false)
     */
    private $outplan;

    /**
     * @var integer
     * 
     * @ORM\Column(name="hsWorkPlan", type="integer", nullable=false)
     */
    private $hsworkplan;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Atstateplan
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atstateplan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="statePlan", referencedColumnName="id")
     * })
     */
    private $stateplan;

    /**
     * @var \att\employeeBundle\Entity\Atemployee
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atemployee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee", referencedColumnName="id")
     * })
     */
    private $employee;

    
    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Atplan
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set inplan
     *
     * @param \DateTime $inplan
     * @return Atplan
     */
    public function setInplan($inplan)
    {
        $this->inplan = $inplan;

        return $this;
    }

    /**
     * Get inplan
     *
     * @return \DateTime 
     */
    public function getInplan()
    {
        return $this->inplan;
    }

    /**
     * Set outplan
     *
     * @param \DateTime $outplan
     * @return Atplan
     */
    public function setOutplan($outplan)
    {
        $this->outplan = $outplan;

        return $this;
    }

    /**
     * Get outplan
     *
     * @return \DateTime 
     */
    public function getOutplan()
    {
        return $this->outplan;
    }

    /**
     * Set hsworkplan
     *
     * @param integer $hsworkplan
     * @return Atplan
     */
    public function setHsworkplan($hsworkplan)
    {
        $this->hsworkplan = $hsworkplan;

        return $this;
    }

    /**
     * Get hsworkplan
     *
     * @return integer 
     */
    public function getHsworkplan()
    {
        return $this->hsworkplan;
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
     * Set stateplan
     *
     * @param \att\attBundle\Entity\Atstateplan $stateplan
     * @return Atplan
     */
    public function setStateplan(\att\attBundle\Entity\Atstateplan $stateplan = null)
    {
        $this->stateplan = $stateplan;

        return $this;
    }

    /**
     * Get stateplan
     *
     * @return \att\attBundle\Entity\Atstateplan 
     */
    public function getStateplan()
    {
        return $this->stateplan;
    }

    /**
     * Set employee
     *
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @return Atplan
     */
    public function setEmployee(\att\employeeBundle\Entity\Atemployee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \att\employeeBundle\Entity\Atemployee 
     */
    public function getEmployee()
    {
        return $this->employee;
    }
    
    
    
    public function __toString() {
        
        
        return nl2br("Plan to: ".$this->getEmployee()->getSurname().", ".$this->getEmployee()->getName()
                ." | ID: ".$this->getEmployee()->getId()."| DNI: ".$this->getEmployee()->getDni()
                ." | to Date ".$this->getDate()->format('Y-m-d')
                ." | Hs To Work: ".$this->getHsworkplan(). "| Income Planned: ".$this->getInplan()->format('H:i'). "| Exit Planned: ".$this->getOutplan()->format('H:i')
                ." | State Planned: ".$this->getStateplan()->getDescription());
        
    }
}
