<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use att\employeeBundle\Entity\Atemployee;
use att\attBundle\Entity\Atcertificatetype;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Atcertificate
 *                                 
 * @ORM\Table(name="att_certificate", indexes={@ORM\Index(name="IXFK_AtCertificate_AtEmployee", columns={"employee"})})
 * @ORM\Entity
 * 
 * 
 * @UniqueEntity(
 *      fields={"employee", "dateto", "datefrom"},
 *      message="Ya se encuentra ingresado un certificado para este rango de fechas",  
 *      errorPath="datefrom"
 * )
 * @UniqueEntity(
 *      fields={"employee", "dateto"},
 *      message="Ya se encuentra ingresado un certificado para la fecha de fin indicada",
 *      errorPath="dateto"
 * )
 * @UniqueEntity(
 *      fields={"employee","datefrom"},
 *      message="Ya se encuentra ingresado un certificado para la fecha de inicio indicada",
 *      errorPath="datefrom"
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * 
 * 
 */
class Atcertificate 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFrom", type="date", nullable=true)
     * 
     */
    private $datefrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateTo", type="date", nullable=true)
     *
     */
    private $dateto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aprobationState", type="boolean", nullable=true)
     */
    private $aprobationstate;   
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="attachDoc", type="boolean", nullable=true)
     */
    private $attachdoc;
    
    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", length=65535, nullable=true)
     */    
    
    private $details;
    
    /**
     * 
     * @var File
     * 
     * @Vich\UploadableField(mapping="certificate_scan", fileNameProperty="scan")
     * @Assert\File(
     *  maxSize = "1000024",
     *  mimeTypes = {"application/pdf", "image/jpeg"},
     *  mimeTypesMessage = "File must have an image format JPG or JPEG digital PDF"
     * )    
     * 
     */
    private $scanFile;    
        
    
    /**
     * @var string
     *
     * @ORM\Column(name="scan", type="text", length=100, nullable=true)
     *      
     */
    private $scan;

    /**
     * @var \att\employeeBundle\Entity\Atemployee
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atemployee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee", referencedColumnName="id")
     * })
     */
    private $employee;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    
     /**
     * @var \att\attBundle\Entity\Atcertificatetype
     *
     * @ORM\ManyToOne(targetEntity="Atcertificatetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;
    

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
     * Set date
     *
     * @param \DateTime $date
     * @return Atcertificate
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set datefrom
     *
     * @param \DateTime $datefrom
     * @return Atcertificate
     */
    public function setDatefrom($datefrom)
    {
        $this->datefrom = $datefrom;

        return $this;
    }

    /**
     * Get datefrom
     *
     * @return \DateTime 
     */
    public function getDatefrom()
    {
        return $this->datefrom;
    }

    /**
     * Set dateto
     *
     * @param \DateTime $dateto
     * @return Atcertificate
     */
    public function setDateto($dateto)
    {
        $this->dateto = $dateto;

        return $this;
    }

    /**
     * Get dateto
     *
     * @return \DateTime 
     */
    public function getDateto()
    {
        return $this->dateto;
    }

    /**
     * Set aprobationstate
     *
     * @param boolean $aprobationstate
     * @return Atcertificate
     */
    public function setAprobationstate($aprobationstate)
    {
        $this->aprobationstate = $aprobationstate;

        return $this;
    }

    /**
     * Get aprobationstate
     *
     * @return boolean 
     */
    public function getAprobationstate()
    {
        return $this->aprobationstate;
    }

     /**
     * Set attachdoc
     *
     * @param boolean $attachdoc
     * @return Atcertificate
     */
    public function setAttachdoc($attachdoc)
    {
        $this->attachdoc = $attachdoc;

        return $this;
    }

    /**
     * Get attachdoc
     *
     * @return boolean 
     */
    public function getAttachdoc()
    {
        return $this->attachdoc;
    }
    
    
    /**
     * Set details
     *
     * @param string $details
     * @return Atcertificate
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set scan
     *
     * @param string $scan
     * @return Atcertificate
     */
    public function setScan($scan)
    {
        $this->scan = $scan;

        return $this;
    }

    /**
     * Get scan
     *
     * @return string 
     */
    public function getScan()
    {
        return $this->scan;
    }

    /**
     * Set employee
     *
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @return Atcertificate
     */
    public function setEmployee(Atemployee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }


    /**
     * Get employee
     *
     * @return \att\employeeBundle\Entity\Atemployee
     */
    public function getEmployee()
    {
        return $this->employee;
    }
    
    
     /**
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Atworkflow
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }
    
    
    
    /**
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $scan
     * @return \att\attBundle\Entity\Atcertificate
     */

    public function setScanFile(\Symfony\Component\HttpFoundation\File\File $scan = null) {
        $this->scanFile = $scan;
        if ($scan){
            $this->setModifiedAt(new \DateTime());
        }
        return $this;
    }
    
    /**
     * 
     * @return File
     */
    public function getScanFile(){
        return $this->scanFile;
    }
    
     /**
     * Set type
     *
     * @param \att\attBundle\Entity\Atcertificatetype $type
     * @return Atcertificate
     */
    public function setType(Atcertificatetype $type = null)
    {
        $this->type = $type;

        return $this;
    }


    /**
     * Get type
     *
     * @return \att\attBundle\Entity\Atcertificatetype
     */
    public function getType()
    {
        return $this->type;
    }
    

    
    
    public function __toString() {
               return 'Certificate Id:'.  $this->getId().' | Nombre: '.$this->getEmployee()->getName().','.$this->getEmployee()->getSurname().' |  Estado: '.$this->getAprobationstate();
            }
        

    /**
     * 
     * @Assert\Callback
     * 
     */
    public function validate(ExecutionContextInterface $context){
        
        
        if(!$this->getId()){
            if (date_diff($this->getDate(), $this->getDatefrom())->d > 300) 
                {
            
                    $context->buildViolation("La fecha de Presentacion no debe exceder las 48 horas desde su fin de licencia")
                        ->atPath('datefrom')
                        ->addViolation();           
                }
        }
       
    }
    
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(){
        $this->setModifiedAt(new \DateTime());
    }
        
    
    
}
