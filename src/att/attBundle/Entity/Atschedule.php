<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atschedule
 *
 * @ORM\Table(name="att_schedule")
 * @ORM\Entity
 */
class Atschedule
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executeDay", type="date", nullable=true)
     */
    private $executeday;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="executeHour", type="time", nullable=true)
     */
    private $executehour;

    /**
     * @var string
     *
     * @ORM\Column(name="command", type="string", length=50, nullable=true)
     */
    private $command;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set executeday
     *
     * @param \DateTime $executeday
     * @return Atschedule
     */
    public function setExecuteday($executeday)
    {
        $this->executeday = $executeday;

        return $this;
    }

    /**
     * Get executeday
     *
     * @return \DateTime 
     */
    public function getExecuteday()
    {
        return $this->executeday;
    }

    /**
     * Set executehour
     *
     * @param \DateTime $executehour
     * @return Atschedule
     */
    public function setExecutehour($executehour)
    {
        $this->executehour = $executehour;

        return $this;
    }

    /**
     * Get executehour
     *
     * @return \DateTime 
     */
    public function getExecutehour()
    {
        return $this->executehour;
    }

    /**
     * Set command
     *
     * @param string $command
     * @return Atschedule
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return string 
     */
    public function getCommand()
    {
        return $this->command;
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
}
