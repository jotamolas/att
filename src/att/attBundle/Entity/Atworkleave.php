<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atworkleave
 *
 * @ORM\Table(name="att_work_leave")
 * @ORM\Entity(repositoryClass="att\attBundle\Repository\AtworkleaveRepository")
 */
class Atworkleave
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_from", type="date")
     */
    private $dateFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_to", type="date")
     */
    private $dateTo;

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
     * @var \att\attBundle\Entity\Atworkleavetype
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atworkleavetype")
     *   @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;
    
    /**
     * @ORM\OneToMany(targetEntity="Atabsence", mappedBy="workleave")
     */
    private $absences;
    
    
    public function __construct() {
        $this->absences = new \Doctrine\Common\Collections\ArrayCollection();
    }

        /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateFrom
     *
     * @param \DateTime $dateFrom
     *
     * @return Atworkleave
     */
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    /**
     * Get dateFrom
     *
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Set dateTo
     *
     * @param \DateTime $dateTo
     *
     * @return Atworkleave
     */
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    /**
     * Get dateTo
     *
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Set employee
     *
     * @param integer $employee
     *
     * @return Atworkleave
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return int
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Atworkleave
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add absence
     *
     * @param \att\attBundle\Entity\Atabsence $absence
     *
     * @return Atworkleave
     */
    public function addAbsence(\att\attBundle\Entity\Atabsence $absence)
    {
        $this->absences[] = $absence;

        return $this;
    }

    /**
     * Remove absence
     *
     * @param \att\attBundle\Entity\Atabsence $absence
     */
    public function removeAbsence(\att\attBundle\Entity\Atabsence $absence)
    {
        $this->absences->removeElement($absence);
    }

    /**
     * Get absences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbsences()
    {
        return $this->absences;
    }
}
