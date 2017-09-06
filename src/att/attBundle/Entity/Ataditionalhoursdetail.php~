<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ataditionalhoursdetail
 *
 * @ORM\Table(name="att_aditional_hours_detail", indexes={@ORM\Index(name="IXFK_AtDetalleHorasExtras_AtAsistencia", columns={"attendance"}), @ORM\Index(name="FK_type_idx", columns={"type"})})
 * @ORM\Entity
 */
class Ataditionalhoursdetail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="attendance", type="integer", nullable=false)
     */
    private $attendance;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
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
     * Set attendance
     *
     * @param integer $attendance
     * @return Ataditionalhoursdetail
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return integer 
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Ataditionalhoursdetail
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
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
     * Set type
     *
     * @param \att\attBundle\Entity\Ataditionalhourstype $type
     * @return Ataditionalhoursdetail
     */
    public function setType(\att\attBundle\Entity\Ataditionalhourstype $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \att\attBundle\Entity\Ataditionalhourstype 
     */
    public function getType()
    {
        return $this->type;
    }
}
