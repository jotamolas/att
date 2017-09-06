<?php

namespace att\ctrlaccBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="ca_device")
 * @ORM\Entity(repositoryClass="att\ctrlaccBundle\Repository\DeviceRepository")
 */
class Device
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \att\ctrlaccBundle\Entity\DeviceType
     * 
     * @ORM\ManyToOne(targetEntity="\att\ctrlaccBundle\Entity\DeviceType")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     * 
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="port", type="string", length=255)
     */
    private $port;
    
    
    
    
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
     * Set description
     *
     * @param string $description
     * @return Device
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param \att\ctrlaccBundle\Entity\DeviceType $type
     * @return Device
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \att\ctrlaccBundle\Entity\DeviceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Device
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }
    
     /**
     * Set port
     *
     * @param string $port
     * @return Device
     */
    public function setPort($port)
    {
        $this->ip = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return string 
     */
    public function getPort()
    {
        return $this->port;
    }
    
}
