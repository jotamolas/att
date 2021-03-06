<?php

namespace att\medicalsrvBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atmedicalservice
 *
 * @ORM\Table(name="ms_medical_service", indexes={@ORM\Index(name="FK_ExternalSystem_idx", columns={"externalSystem"})})
 * @ORM\Entity
 */
class Atmedicalservice {

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\syncBundle\Entity\Atexternsystem
     *
     * @ORM\ManyToOne(targetEntity="\att\syncBundle\Entity\Atexternsystem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="externalSystem", referencedColumnName="id")
     * })
     */
    private $externalsystem;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=50, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="cuit", type="string", length=50, nullable=true)
     */
    private $cuit;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="state", type="boolean", nullable=true)
     */
    private $state;
    

    /**
     * Set name
     *
     * @param string $name
     * @return Atmedicalservice
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set externsystem
     *
     * @param \att\syncBundle\Entity\Atexternsystem $externalsystem
     * @return Atmedicalservice
     */
    public function setExternalsystem(\att\syncBundle\Entity\Atexternsystem $externalsystem = null) {
        $this->externalsystem = $externalsystem;

        return $this;
    }

    /**
     * Get externsystem
     *
     * @return \att\syncBundle\Entity\Atexternsystem 
     */
    public function getExternalsystem() {
        return $this->externalsystem;
    }


    /**
     * Set token
     *
     * @param string $token
     *
     * @return Atmedicalservice
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     *
     * @return Atmedicalservice
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return Atmedicalservice
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }
}
