<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atevent
 *
 * @ORM\Table(name="att_event", indexes={@ORM\Index(name="FK_schedule_idx", columns={"schedule"}), @ORM\Index(name="FK_att_idx", columns={"attendance"}), @ORM\Index(name="FK_event_idx", columns={"eventType"})})
 * @ORM\Entity
 */
class Atevent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Ateventtype
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Ateventtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eventType", referencedColumnName="id")
     * })
     */
    private $eventtype;

    /**
     * @var \att\attBundle\Entity\Atattendance
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atattendance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attendance", referencedColumnName="id")
     * })
     */
    private $attendance;

    /**
     * @var \att\attBundle\Entity\Atschedule
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atschedule")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="schedule", referencedColumnName="id")
     * })
     */
    private $schedule;



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
     * Set eventtype
     *
     * @param \att\attBundle\Entity\Ateventtype $eventtype
     * @return Atevent
     */
    public function setEventtype(\att\attBundle\Entity\Ateventtype $eventtype = null)
    {
        $this->eventtype = $eventtype;

        return $this;
    }

    /**
     * Get eventtype
     *
     * @return \att\attBundle\Entity\Ateventtype 
     */
    public function getEventtype()
    {
        return $this->eventtype;
    }

    /**
     * Set attendance
     *
     * @param \att\attBundle\Entity\Atattendance $attendance
     * @return Atevent
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
     * Set schedule
     *
     * @param \att\attBundle\Entity\Atschedule $schedule
     * @return Atevent
     */
    public function setSchedule(\att\attBundle\Entity\Atschedule $schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return \att\attBundle\Entity\Atschedule 
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
