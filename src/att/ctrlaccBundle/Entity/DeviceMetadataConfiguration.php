<?php

namespace att\ctrlaccBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceMetadataConfiguration
 *
 * @ORM\Table(name="ca_device_metadata_configuration")
 * @ORM\Entity(repositoryClass="att\ctrlaccBundle\Repository\DeviceMetadataConfigurationRepository")
 */
class DeviceMetadataConfiguration
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
     * @ORM\Column(name="table_or_method", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $table_or_method;

    /**
     * @var int
     *
     * @ORM\Column(name="id_attribute", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $id_attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="eventdate_attribute", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $eventdate_attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="eventime_attribute", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $eventime_attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="employee_attribute", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $employee_attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="event_attribute", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $event_attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="in_event_value", type="string", length=255)
     * @Assert\NotBlank()
     * 
     */
    private $inEvent_value;

    /**
     * @var string
     *
     * @ORM\Column(name="out_event_value", type="string", length=255)
     * @Assert\NotBlank()
     * 
     */
    private $outEvent_value;

    /**
     * @var \att\ctrlaccBundle\Entity\Device
     *
     * @ORM\OneToOne(targetEntity="\att\ctrlaccBundle\Entity\Device", inversedBy="metadata")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="device", referencedColumnName="id")
     * })
     * @Assert\NotBlank()
     */
    private $device;    




    

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
     * Set tableOrMethod
     *
     * @param string $tableOrMethod
     *
     * @return DeviceMetadataConfiguration
     */
    public function setTableOrMethod($tableOrMethod)
    {
        $this->table_or_method = $tableOrMethod;

        return $this;
    }

    /**
     * Get tableOrMethod
     *
     * @return string
     */
    public function getTableOrMethod()
    {
        return $this->table_or_method;
    }

    /**
     * Set idAttribute
     *
     * @param string $idAttribute
     *
     * @return DeviceMetadataConfiguration
     */
    public function setIdAttribute($idAttribute)
    {
        $this->id_attribute = $idAttribute;

        return $this;
    }

    /**
     * Get idAttribute
     *
     * @return string
     */
    public function getIdAttribute()
    {
        return $this->id_attribute;
    }

    /**
     * Set eventdateAttribute
     *
     * @param string $eventdateAttribute
     *
     * @return DeviceMetadataConfiguration
     */
    public function setEventdateAttribute($eventdateAttribute)
    {
        $this->eventdate_attribute = $eventdateAttribute;

        return $this;
    }

    /**
     * Get eventdateAttribute
     *
     * @return string
     */
    public function getEventdateAttribute()
    {
        return $this->eventdate_attribute;
    }

    /**
     * Set eventimeAttribute
     *
     * @param string $eventimeAttribute
     *
     * @return DeviceMetadataConfiguration
     */
    public function setEventimeAttribute($eventimeAttribute)
    {
        $this->eventime_attribute = $eventimeAttribute;

        return $this;
    }

    /**
     * Get eventimeAttribute
     *
     * @return string
     */
    public function getEventimeAttribute()
    {
        return $this->eventime_attribute;
    }

    /**
     * Set employeeAttribute
     *
     * @param string $employeeAttribute
     *
     * @return DeviceMetadataConfiguration
     */
    public function setEmployeeAttribute($employeeAttribute)
    {
        $this->employee_attribute = $employeeAttribute;

        return $this;
    }

    /**
     * Get employeeAttribute
     *
     * @return string
     */
    public function getEmployeeAttribute()
    {
        return $this->employee_attribute;
    }

    /**
     * Set eventAttribute
     *
     * @param string $eventAttribute
     *
     * @return DeviceMetadataConfiguration
     */
    public function setEventAttribute($eventAttribute)
    {
        $this->event_attribute = $eventAttribute;

        return $this;
    }

    /**
     * Get eventAttribute
     *
     * @return string
     */
    public function getEventAttribute()
    {
        return $this->event_attribute;
    }

    /**
     * Set inEventValue
     *
     * @param string $inEventValue
     *
     * @return DeviceMetadataConfiguration
     */
    public function setInEventValue($inEventValue)
    {
        $this->inEvent_value = $inEventValue;

        return $this;
    }

    /**
     * Get inEventValue
     *
     * @return string
     */
    public function getInEventValue()
    {
        return $this->inEvent_value;
    }

    /**
     * Set outEventValue
     *
     * @param string $outEventValue
     *
     * @return DeviceMetadataConfiguration
     */
    public function setOutEventValue($outEventValue)
    {
        $this->outEvent_value = $outEventValue;

        return $this;
    }

    /**
     * Get outEventValue
     *
     * @return string
     */
    public function getOutEventValue()
    {
        return $this->outEvent_value;
    }

    /**
     * Set device
     *
     * @param \att\ctrlaccBundle\Entity\Device $device
     *
     * @return DeviceMetadataConfiguration
     */
    public function setDevice(\att\ctrlaccBundle\Entity\Device $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return \att\ctrlaccBundle\Entity\Device
     */
    public function getDevice()
    {
        return $this->device;
    }
}
