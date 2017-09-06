<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Atabsencenotification
 *
 * @ORM\Table(name="att_absence_notification", 
 * indexes={@ORM\Index(name="IXFK_Notification_Employee", columns={"employee"}), @ORM\Index(name="IXFK_Notification_AbsenceType", columns={"absenceType"})})
 * @ORM\Entity(repositoryClass="att\attBundle\Repository\NotificationRepository")
 * @UniqueEntity(
 *      fields={"employee", "fromdate", "todate"},
 *      message="notification.employee.fromdatetodate.unique",  
 *      errorPath="todate"
 * )
 * @UniqueEntity(
 *      fields={"employee", "todate"},
 *      message="notification.employee.todate.unique",
 *      errorPath="todate"
 * )
 * @UniqueEntity(
 *      fields={"employee","fromdate"},
 *      message="notification.employee.fromdate.unique",
 *      errorPath="fromdate"
 * )
 * )
 */
class Atabsencenotification
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="days", type="integer", nullable=false)
     */
    private $days;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fromDate", type="date", nullable=false)
     */
    private $fromdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="toDate", type="date", nullable=false)
     */
    private $todate;

    /**
     * @var String
     * 
     * @ORM\Column(name="code", type="string", nullable=false) 
     */
    private $code;
    
    
    /**
     * @var String
     * 
     * @ORM\Column(name="subject", type="string", nullable=true) 
     */
    private $subject;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atworkleavetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="absenceType", referencedColumnName="id")
     * })
     */
    private $absencetype;



    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Atabsencenotification
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
     * Set days
     *
     * @param integer $days
     * @return Atabsencenotification
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set fromdate
     *
     * @param \DateTime $fromdate
     * @return Atabsencenotification
     */
    public function setFromdate($fromdate)
    {
        $this->fromdate = $fromdate;

        return $this;
    }

    /**
     * Get fromdate
     *
     * @return \DateTime 
     */
    public function getFromdate()
    {
        return $this->fromdate;
    }

    /**
     * Set todate
     *
     * @param \DateTime $todate
     * @return Atabsencenotification
     */
    public function setTodate($todate)
    {
        $this->todate = $todate;

        return $this;
    }

    /**
     * Get todate
     *
     * @return \DateTime 
     */
    public function getTodate()
    {
        return $this->todate;
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
     * Set code
     * @param string $code
     * @return \att\attBundle\Entity\Atabsencenotification
     */
    public function setCode($code){
        $this->code = $code;
        return $this;
    }
    
    /**
     * Get code
     * @return string
     */
    public function getCode(){
        
        return $this->code;
        
    }

    
    /**
     * Set employee
     *
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @return Atabsencenotification
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


    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Atabsencenotification
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set absencetype
     *
     * @param \att\attBundle\Entity\Atworkleavetype $absencetype
     *
     * @return Atabsencenotification
     */
    public function setAbsencetype(\att\attBundle\Entity\Atworkleavetype $absencetype = null)
    {
        $this->absencetype = $absencetype;

        return $this;
    }

    /**
     * Get absencetype
     *
     * @return \att\attBundle\Entity\Atworkleavetype
     */
    public function getAbsencetype()
    {
        return $this->absencetype;
    }
}
