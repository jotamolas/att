<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atcertificatetype
 *
 * @ORM\Table(name="att_certificate_type")
 * @ORM\Entity
 */
class Atcertificatetype
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
     * 
     * @ORM\OneToMany(targetEntity="Atworkleavetype", mappedBy="certificatetype")
     */
    private $workleavetypes;

    public function __construct() {
        $this->workleavetypes = new \Doctrine\Common\Collections\ArrayCollection() ;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Atcertificatetype
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
     * Add workleavetype
     *
     * @param \att\attBundle\Entity\Atworkleavetype $workleavetype
     *
     * @return Atcertificatetype
     */
    public function addWorkleavetype(\att\attBundle\Entity\Atworkleavetype $workleavetype)
    {
        $this->workleavetypes[] = $workleavetype;

        return $this;
    }

    /**
     * Remove workleavetype
     *
     * @param \att\attBundle\Entity\Atworkleavetype $workleavetype
     */
    public function removeWorkleavetype(\att\attBundle\Entity\Atworkleavetype $workleavetype)
    {
        $this->workleavetypes->removeElement($workleavetype);
    }

    /**
     * Get workleavetypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWorkleavetypes()
    {
        return $this->workleavetypes;
    }
}
