<?php

namespace att\ctrlaccBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="ca_event")
 * @ORM\Entity(repositoryClass="att\ctrlaccBundle\Repository\EventRepository")
 */
class Event
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
     * @var int
     *
     * @ORM\Column(name="id_original", type="integer", unique=true)
     */
    private $idoriginal;

    /**
     * @var int
     *
     * @ORM\Column(name="id_device", type="integer")
     */
    private $iddevice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_date", type="date")
     */
    private $eventdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_time", type="datetime")
     */
    private $eventtime;

    /**
     * @var int
     *
     * @ORM\Column(name="employee", type="integer")
     */
    private $employee;

    /**
     *
     * @var int
     * @ORM\Column(name="event_type", type="integer") 
     */
    private $eventtype;

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
     * Set idoriginal
     *
     * @param integer $idoriginal
     * @return Event
     */
    public function setIdoriginal($idoriginal)
    {
        $this->idoriginal = $idoriginal;

        return $this;
    }

    /**
     * Get idoriginal
     *
     * @return integer 
     */
    public function getIdoriginal()
    {
        return $this->idoriginal;
    }

    /**
     * Set iddevice
     *
     * @param integer $iddevice
     * @return Event
     */
    public function setIddevice($iddevice)
    {
        $this->iddevice = $iddevice;

        return $this;
    }

    /**
     * Get iddevice
     *
     * @return integer 
     */
    public function getIddevice()
    {
        return $this->iddevice;
    }

    /**
     * Set eventdate
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setEventdate($date)
    {
        $this->eventdate = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * Set eventtime
     *
     * @param \DateTime $time
     * @return Event
     */
    public function setEventtime($time)
    {
        $this->eventtime = $time;

        return $this;
    }

    /**
     * Get eventtime
     *
     * @return \DateTime 
     */
    public function getEventtime()
    {
        return $this->eventtime;
    }

    /**
     * Set employee
     *
     * @param integer $employee
     * @return Event
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return integer 
     */
    public function getEmployee()
    {
        return $this->employee;
    }
    
    
    /**
     * Set eventtype
     *
     * @param integer $type
     * @return Event
     */
    public function setEventtype($type)
    {
        $this->eventtype = $type;

        return $this;
    }

    /**
     * Get eventtype
     *
     * @return integer 
     */
    public function getEventtype()
    {
        return $this->eventtype;
    }
    
    
    
}
