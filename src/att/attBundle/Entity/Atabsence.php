<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Atabsence
 *
 * @ORM\Table(
 *      name="att_absence", 
 *      uniqueConstraints={@ORM\UniqueConstraint(name="UQ_AtInasistencia_idAsistencia", columns={"attendance"})}, 
 *      indexes={@ORM\Index(name="IXFK_Absence_Attendance", columns={"attendance"}), @ORM\Index(name="IXFK_Absence_Certification", columns={"certification"})})
 * @ORM\Entity(repositoryClass="att\attBundle\Repository\AtabsenceRepository")
 * @UniqueEntity("attendance")
 */
class Atabsence
{
    /**
     * @var integer
     *
     * @ORM\Column(name="certification", type="integer", nullable=true)
     */
    private $certification;

    /**
     * @var binary
     *
     * @ORM\Column(name="stateJustif", type="binary", nullable=true)
     */
    private $statejustif;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Atattendance
     *
     * 
     *
     * @ORM\OneToOne(targetEntity="\att\attBundle\Entity\Atattendance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attendance", referencedColumnName="id")
     * })
     */
    private $attendance;



    
    /**
     * @var \att\attBundle\Entity\Atworkleave
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atworkleave",inversedBy="absences")
     * @ORM\JoinColumn(name="workleave", referencedColumnName="id")
     * 
     */
    private $workleave;

    /**
     * Set certification
     *
     * @param integer $certification
     * @return Atabsence
     */
    public function setCertification($certification)
    {
        $this->certification = $certification;

        return $this;
    }

    /**
     * Get certification
     *
     * @return integer 
     */
    public function getCertification()
    {
        return $this->certification;
    }

    /**
     * Set statejustif
     *
     * @param binary $statejustif
     * @return Atabsence
     */
    public function setStatejustif($statejustif)
    {
        $this->statejustif = $statejustif;

        return $this;
    }

    /**
     * Get statejustif
     *
     * @return binary 
     */
    public function getStatejustif()
    {
        return $this->statejustif;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Atabsence
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set attendance
     *
     * @param \att\attBundle\Entity\Atattendance $attendance
     * @return Atabsence
     */
    public function setAttendance(\att\attBundle\Entity\Atattendance $attendance)
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


    
    public function __toString() {
        return "Absence | ID:".$this->getId()." | Certification WorkFlow: ".$this->certification;
    }

    /**
     * Set workleave
     *
     * @param \att\attBundle\Entity\Atworkleave $workleave
     *
     * @return Atabsence
     */
    public function setWorkleave(\att\attBundle\Entity\Atworkleave $workleave = null)
    {
        $this->workleave = $workleave;

        return $this;
    }

    /**
     * Get workleave
     *
     * @return \att\attBundle\Entity\Atworkleave
     */
    public function getWorkleave()
    {
        return $this->workleave;
    }
}
