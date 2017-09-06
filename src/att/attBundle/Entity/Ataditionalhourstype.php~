<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ataditionalhourstype
 *
 * @ORM\Table(name="att_aditional_hours_type", indexes={@ORM\Index(name="FK_agreement_idx", columns={"Agreement"})})
 * @ORM\Entity
 */
class Ataditionalhourstype
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=45, nullable=false)
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
     * @var \att\employeeBundle\Entity\Atagreement
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atagreement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agreement", referencedColumnName="id")
     * })
     */
    private $agreement;



    /**
     * Set name
     *
     * @param string $name
     * @return Ataditionalhourstype
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
     * Set description
     *
     * @param string $description
     * @return Ataditionalhourstype
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
     * Set agreement
     *
     * @param \att\employeeBundle\Entity\Atagreement $agreement
     * @return Ataditionalhourstype
     */
    public function setAgreement(\att\employeeBundle\Entity\Atagreement $agreement = null)
    {
        $this->agreement = $agreement;

        return $this;
    }

    /**
     * Get agreement
     *
     * @return \att\employeeBundle\Entity\Atagreement 
     */
    public function getAgreement()
    {
        return $this->agreement;
    }
}
