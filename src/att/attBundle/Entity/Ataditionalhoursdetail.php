<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ataditionalhoursdetail
 *
 * @ORM\Table(name="att_aditional_hours_detail", indexes={@ORM\Index(name="IXFK_AtDetalleHorasExtras_AtAsistencia", columns={"attendance"}), @ORM\Index(name="FK_type_idx", columns={"type"})})
 * @ORM\Entity
 */
class Ataditionalhoursdetail {

    /**
     * @var \att\attBundle\Entity\Atattendance
     *
     * 
     *
     * @ORM\OneToOne(targetEntity="\att\attBundle\Entity\Atattendance",inversedBy="aditionalhours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attendance", referencedColumnName="id")
     * })
     */
    private $attendance;

    /**
     * @var decimal
     *
     * @ORM\Column(name="amount", type="decimal", precision=4, scale=2, nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Ataditionalhourstype
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Ataditionalhourstype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;
    
    /**
     *
     * @var boolean
     * @ORM\Column(name="is_approved", type="boolean")
     */
    private $isapproved = false;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param \att\attBundle\Entity\Ataditionalhourstype $type
     * @return Ataditionalhoursdetail
     */
    public function setType(\att\attBundle\Entity\Ataditionalhourstype $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \att\attBundle\Entity\Ataditionalhourstype 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Ataditionalhoursdetail
     */
    public function setAmount($amount) {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount() {
        return $this->amount;
    }


    /**
     * Set attendance
     *
     * @param \att\attBundle\Entity\Atattendance $attendance
     *
     * @return Ataditionalhoursdetail
     */
    public function setAttendance(\att\attBundle\Entity\Atattendance $attendance = null)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return \att\attBundle\Entity\Atattendance
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set isapproved
     *
     * @param boolean $isapproved
     *
     * @return Ataditionalhoursdetail
     */
    public function setIsapproved($isapproved)
    {
        $this->isapproved = $isapproved;

        return $this;
    }

    /**
     * Get isapproved
     *
     * @return boolean
     */
    public function getIsapproved()
    {
        return $this->isapproved;
    }
}
