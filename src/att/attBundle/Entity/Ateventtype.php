<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ateventtype
 *
 * @ORM\Table(name="att_event_type", indexes={@ORM\Index(name="FK_ExternalSystem_idx", columns={"externalSystem"}), @ORM\Index(name="FK_ExSys_idx", columns={"externalSystem"})})
 * @ORM\Entity
 */
class Ateventtype
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=false)
     */
    private $description;

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
     * Set description
     *
     * @param string $description
     * @return Ateventtype
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set externsystem
     *
     * @param \att\syncBundle\Entity\Atexternsystem $externalsystem
     * @return Ateventtype
     */
    public function setExternalsystem(\att\syncBundle\Entity\Atexternsystem $externalsystem = null)
    {
        $this->externalsystem = $externalsystem;

        return $this;
    }

    /**
     * Get externalsystem
     *
     * @return \att\syncBundle\Entity\Atexternsystem 
     */
    public function getExternalsystem()
    {
        return $this->externalsystem;
    }
}
