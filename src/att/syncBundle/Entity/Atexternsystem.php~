<?php

namespace att\syncBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Atexternsystem
 *
 * @ORM\Table(name="sync_extern_system", indexes={@ORM\Index(name="FK_Type_idx", columns={"type"})})
 * @ORM\Entity
 */
class Atexternsystem
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=50, nullable=false)
     * @Assert\Ip
     */

    private $ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=false)
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=45, nullable=true)
     */
    private $module;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\syncBundle\Entity\Atexternsystemtype
     *
     * @ORM\ManyToOne(targetEntity="\att\syncBundle\Entity\Atexternsystemtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;



    /**
     * Set name
     *
     * @param string $name
     * @return Atexternsystem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Atexternsystem
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
     * @param integer $port
     * @return Atexternsystem
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set module
     *
     * @param string $module
     * @return Atexternsystem
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string 
     */
    public function getModule()
    {
        return $this->module;
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
     * 
     *
     * @param \att\syncBundle\Entity\Atexternsystemtype $type
     * @return Atexternsystem
     */
    public function setType(\att\syncBundle\Entity\Atexternsystemtype $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \att\attBundle\Entity\Atexternsystemtype 
     */
    public function getType()
    {
        return $this->type;
    }
}
