<?php

namespace att\ctrlaccBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="ca_device")
 * @ORM\Entity
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
     * @var \att\syncBundle\Entity\Atexternsystem
     *
     * @ORM\OneToOne(targetEntity="\att\syncBundle\Entity\Atexternsystem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="system", referencedColumnName="id")
     * })
     */
    private $system;
    
    /**
     * @ORM\OneToOne(targetEntity="\att\ctrlaccBundle\Entity\DeviceMetadataConfiguration", mappedBy="device")
     * @var type 
     */
    private $metadata;
    
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
     * Set system
     *
     * @param \att\syncBundle\Entity\Atexternsystem $system
     *
     * @return Device
     */
    public function setSystem(\att\syncBundle\Entity\Atexternsystem $system = null)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return \att\syncBundle\Entity\Atexternsystem
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Set metadata
     *
     * @param \att\ctrlaccBundle\Entity\DeviceMetadataConfiguration $metadata
     *
     * @return Device
     */
    public function setMetadata(\att\ctrlaccBundle\Entity\DeviceMetadataConfiguration $metadata = null)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Get metadata
     *
     * @return \att\ctrlaccBundle\Entity\DeviceMetadataConfiguration
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
