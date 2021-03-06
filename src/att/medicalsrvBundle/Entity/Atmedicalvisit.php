<?php

namespace att\medicalsrvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atmedicalvisit
 *
 * @ORM\Table(name="ms_medical_visit")
 * @ORM\Entity
 */
class Atmedicalvisit {

    /**
     * @var \att\medicalsrvBundle\Entity\Atmedicalorder
     *
     * @ORM\ManyToOne(targetEntity="\att\medicalsrvBundle\Entity\Atmedicalorder", inversedBy="medicalvisits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="medical_order", referencedColumnName="id")
     * })
     */
    private $medicalOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="datetime", nullable=true)
     */
    private $visitdate;

    /**
     * @var string
     *
     * @ORM\Column(name="matricula", type="string", length=50, nullable=true)
     */
    private $matricula;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostic", type="text", length=65535, nullable=true)
     */
    private $diagnostic;

    /**
     * @var boolean
     *
     * @ORM\Column(name="medicalRest", type="boolean", nullable=true)
     */
    private $medicalrest;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="restDateFrom", type="datetime", nullable=true)
     */
    private $restdatefrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="restDateTo", type="datetime", nullable=true)
     */
    private $restdateto;
    
    
    /**
     * @var \boolean
     *
     * @ORM\Column(name="confirm_order", type="boolean", nullable=true)
     */
    private $confirmOrder;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\medicalsrvBundle\Entity\Atmedicalvisitstatus
     *
     * @ORM\ManyToOne(targetEntity="\att\medicalsrvBundle\Entity\Atmedicalvisitstatus",cascade={"persist"}))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id")
     * })
     */
    private $status;
    
    /**
     * Set visitdate
     *
     * @param \DateTime $visitdate
     * @return Atmedicalvisit
     */
    public function setVisitdate($visitdate) {
        $this->visitdate = $visitdate;

        return $this;
    }

    /**
     * Get visitdate
     *
     * @return \DateTime 
     */
    public function getVisitdate() {
        return $this->visitdate;
    }

    /**
     * Set matricula
     *
     * @param string $matricula
     * @return Atmedicalvisit
     */
    public function setMatricula($matricula) {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return string 
     */
    public function getMatricula() {
        return $this->matricula;
    }

    /**
     * Set diagnostic
     *
     * @param string $diagnostic
     * @return Atmedicalvisit
     */
    public function setDiagnostic($diagnostic) {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    /**
     * Get diagnostic
     *
     * @return string 
     */
    public function getDiagnostic() {
        return $this->diagnostic;
    }



    /**
     * Set restdatefrom
     *
     * @param \DateTime $restdatefrom
     * @return Atmedicalvisit
     */
    public function setRestdatefrom($restdatefrom) {
        $this->restdatefrom = $restdatefrom;

        return $this;
    }

    /**
     * Get restdatefrom
     *
     * @return \DateTime 
     */
    public function getRestdatefrom() {
        return $this->restdatefrom;
    }

    /**
     * Set restdateto
     *
     * @param \DateTime $restdateto
     * @return Atmedicalvisit
     */
    public function setRestdateto($restdateto) {
        $this->restdateto = $restdateto;

        return $this;
    }

    /**
     * Get restdateto
     *
     * @return \DateTime 
     */
    public function getRestdateto() {
        return $this->restdateto;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Set medicalOrder
     *
     * @param \att\medicalsrvBundle\Entity\Atmedicalorder $medicalOrder
     *
     * @return Atmedicalvisit
     */
    public function setMedicalOrder(\att\medicalsrvBundle\Entity\Atmedicalorder $medicalOrder = null)
    {
        $this->medicalOrder = $medicalOrder;

        return $this;
    }

    /**
     * Get medicalOrder
     *
     * @return \att\medicalsrvBundle\Entity\Atmedicalorder
     */
    public function getMedicalOrder()
    {
        return $this->medicalOrder;
    }

    /**
     * Set status
     *
     * @param \att\medicalsrvBundle\Entity\Atmedicalvisitstatus $status
     *
     * @return Atmedicalvisit
     */
    public function setStatus(\att\medicalsrvBundle\Entity\Atmedicalvisitstatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \att\medicalsrvBundle\Entity\Atmedicalvisitstatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set medicalrest
     *
     * @param boolean $medicalrest
     *
     * @return Atmedicalvisit
     */
    public function setMedicalrest($medicalrest)
    {
        $this->medicalrest = $medicalrest;

        return $this;
    }

    /**
     * Get medicalrest
     *
     * @return boolean
     */
    public function getMedicalrest()
    {
        return $this->medicalrest;
    }

    /**
     * Set confirmOrder
     *
     * @param boolean $confirmOrder
     *
     * @return Atmedicalvisit
     */
    public function setConfirmOrder($confirmOrder)
    {
        $this->confirmOrder = $confirmOrder;

        return $this;
    }

    /**
     * Get confirmOrder
     *
     * @return boolean
     */
    public function getConfirmOrder()
    {
        return $this->confirmOrder;
    }
}
