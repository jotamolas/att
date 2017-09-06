<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atlicense
 *
 * @ORM\Table(name="att_work_leave_type")
 * @ORM\Entity
 */
class Atworkleavetype
{
    /**
     * @var integer
     *
     * @ORM\Column(name="maxDay", type="integer", nullable=false)
     */
    private $maxday;

    /**
     * @var integer
     *
     * @ORM\Column(name="maxdaysseq", type="integer", nullable=true)
     */
    private $maxdaysseq;

    /**
     * @var binary
     *
     * @ORM\Column(name="certification", type="binary", nullable=false)
     */
    private $is_justifiable;

    /**
     * @var binary
     *
     * @ORM\Column(name="payment", type="binary", nullable=false)
     */
    private $is_payable;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=false)
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
     * @var \att\employeeBundle\Entity\Atagreement
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atagreement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agreement", referencedColumnName="id")
     * })
     */
    private $agreement;





    /**
     * Set maxday
     *
     * @param integer $maxday
     *
     * @return Atworkleavetype
     */
    public function setMaxday($maxday)
    {
        $this->maxday = $maxday;

        return $this;
    }

    /**
     * Get maxday
     *
     * @return integer
     */
    public function getMaxday()
    {
        return $this->maxday;
    }

    /**
     * Set maxdaysseq
     *
     * @param integer $maxdaysseq
     *
     * @return Atworkleavetype
     */
    public function setMaxdaysseq($maxdaysseq)
    {
        $this->maxdaysseq = $maxdaysseq;

        return $this;
    }

    /**
     * Get maxdaysseq
     *
     * @return integer
     */
    public function getMaxdaysseq()
    {
        return $this->maxdaysseq;
    }

    /**
     * Set isJustifiable
     *
     * @param binary $isJustifiable
     *
     * @return Atworkleavetype
     */
    public function setIsJustifiable($isJustifiable)
    {
        $this->is_justifiable = $isJustifiable;

        return $this;
    }

    /**
     * Get isJustifiable
     *
     * @return binary
     */
    public function getIsJustifiable()
    {
        return $this->is_justifiable;
    }

    /**
     * Set isPayable
     *
     * @param binary $isPayable
     *
     * @return Atworkleavetype
     */
    public function setIsPayable($isPayable)
    {
        $this->is_payable = $isPayable;

        return $this;
    }

    /**
     * Get isPayable
     *
     * @return binary
     */
    public function getIsPayable()
    {
        return $this->is_payable;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Atworkleavetype
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
     * Set agreement
     *
     * @param \att\employeeBundle\Entity\Atagreement $agreement
     *
     * @return Atworkleavetype
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
