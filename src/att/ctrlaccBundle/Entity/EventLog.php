<?php

namespace att\ctrlaccBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventLog
 *
 * @ORM\Table(name="ca_event_log")
 * @ORM\Entity
 */
class EventLog
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
     * @ORM\Column(name="logDate", type="datetime")
     */
    private $logDate;

    /**
     * @var int
     *
     * @ORM\Column(name="lasteventid", type="integer")
     */
    private $lasteventid;


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
     * Set logDate
     *
     * @param \DateTime $logDate
     * @return EventLog
     */
    public function setLogDate($logDate)
    {
        $this->logDate = $logDate;

        return $this;
    }

    /**
     * Get logDate
     *
     * @return \DateTime 
     */
    public function getLogDate()
    {
        return $this->logDate;
    }

    /**
     * Set lasteventid
     *
     * @param integer $lasteventid
     * @return EventLog
     */
    public function setLasteventid($lasteventid)
    {
        $this->lasteventid = $lasteventid;

        return $this;
    }

    /**
     * Get lasteventid
     *
     * @return integer 
     */
    public function getLasteventid()
    {
        return $this->lasteventid;
    }
}
