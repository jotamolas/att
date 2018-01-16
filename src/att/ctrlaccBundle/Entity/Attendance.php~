<?php

namespace att\ctrlaccBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Attendance
 *
 * @ORM\Table(name="ca_attendance")
 * @ORM\Entity(repositoryClass="att\ctrlaccBundle\Repository\AttendanceRepository")
 * 
 * @UniqueEntity(
 *      fields={"employee", "date"},
 *      message="Already an attendance record for the employee on the day indicated",  
 *      errorPath="date"
 * )
 * 
 * 
 */
class Attendance
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
     * @ORM\Column(name="employee", type="string")
     * @Assert\NotBlank()
     * 
     */
    private $employee;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank()
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inEvent", type="datetime")
     * @Assert\NotBlank()
     */
    private $inEvent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="outEvent", type="datetime", nullable=true)
     */
    private $outEvent;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hoursWorkedTime", type="time", nullable=true)
     */
    private $hoursWorkedTime;


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
     * Set employee
     *
     * @param string $employee
     * @return Attendance
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return string 
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Attendance
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
     * Set inEvent
     *
     * @param \DateTime $inEvent
     * @return Attendance
     */
    public function setInEvent($inEvent)
    {
        $this->inEvent = $inEvent;

        return $this;
    }

    /**
     * Get inEvent
     *
     * @return \DateTime 
     */
    public function getInEvent()
    {
        return $this->inEvent;
    }

    /**
     * Set outEvent
     *
     * @param \DateTime $outEvent
     * @return Attendance
     */
    public function setOutEvent($outEvent)
    {
        $this->outEvent = $outEvent;

        return $this;
    }

    /**
     * Get outEvent
     *
     * @return \DateTime 
     */
    public function getOutEvent()
    {
        return $this->outEvent;
    }



    /**
     * Set hoursWorkedTime
     *
     * @param \DateTime $hoursWorkedTime
     * @return Attendance
     */
    public function setHoursWorkedTime($hoursWorkedTime)
    {
        $this->hoursWorkedTime = $hoursWorkedTime;

        return $this;
    }

    /**
     * Get hoursWorkedTime
     *
     * @return \DateTime 
     */
    public function getHoursWorkedTime()
    {
        return $this->hoursWorkedTime;
    }
    
    
   public function __toString() {
        return "Attendace | Id: ". $this->getId()." "
                . " Date: ".($this->getDate() ? $this->getDate()->format('Y-m-d'): NULL)." "
                . " Employee: ".$this->getEmployee()." "
                . " In: ".($this->getInEvent() ? $this->getInEvent()->format('H:i:s'):NULL).""
                . " Out: ".($this->getOutEvent() ? $this->getOutEvent()->format('H:i:s'): NULL).""
                . " HsWorked: ".($this->getHoursWorkedTime() ? $this->getHoursWorkedTime()->format('H:i') : NULL );
    }
}
