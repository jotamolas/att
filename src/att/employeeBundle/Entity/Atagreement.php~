<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atagreement
 *
 * @ORM\Table(name="emp_agreement", indexes={@ORM\Index(name="FK_Union_idx", columns={"union"})})
 * @ORM\Entity
 */
class Atagreement
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=true)
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
     * @var \att\employeeBundle\Entity\Atunion
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atunion",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="union", referencedColumnName="id")
     * })
     */
    private $union;



    /**
     * Set description
     *
     * @param string $description
     * @return Atagreement
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
     * Set union
     *
     * @param \att\employeeBundle\Entity\Atunion $union
     * @return Atagreement
     */
    public function setUnion(\att\employeeBundle\Entity\Atunion $union = null)
    {
        $this->union = $union;

        return $this;
    }

    /**
     * Get union
     *
     * @return \att\employeeBundle\Entity\Atunion 
     */
    public function getUnion()
    {
        return $this->union;
    }
    
    
    public function __toString() {
        return $this->getDescription()." | Union: ". $this->getUnion()->getDescription();
    }
}
