<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Atcompany
 * 
 * @ORM\Table(name="emp_company"
 *      )
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtcompanyRepository")
 * 
 */
class Atcompany
{
    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=45, nullable=false)
     * @Assert\NotBlank
     */
    private $description;
    
    /**
     * @var text
     * @ORM\Column(name="obs", type="text", nullable=true)
     */
    
    private $obs;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * 
     */
    private $id;


    /**
     * Set desc
     *
     * @param string $description
     * @return Atcompany
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
     * Set obs
     *
     * @param string $obs
     * @return Atcompany
     */
    public function setObs($obs)
    {
        $this->obs = $obs;

        return $this;
    }

    /**
     * Get obs
     *
     * @return string 
     */
    public function getObs()
    {
        return $this->obs;
    }
}
