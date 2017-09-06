<?php //

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atcertificatemedical
 *
 * @ORM\Table(name="att_certificate_stude", indexes={@ORM\Index(name="FK_Certificate_Stude_idx", columns={"certificate"})})
 * @ORM\Entity
 */
class Atcertificatestude
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
     * @var string
     *
     * @ORM\Column(name="institute", type="string", length=50, nullable=false)
     */
    private $institute;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateExam", type="date", nullable=false)
     */
    private $dateexam;

    
    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=50, nullable=false)
     */
    private $subject;

    
    /**
     * @var \Atcertificado
     *
     * @ORM\OneToOne(targetEntity="Atcertificate" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="certificate", referencedColumnName="id")
     * 
     */
    private $certificate;



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
     * @param string $institute
     * @return Atcertificatestude
     */
    public function setInstitute($institute)
    {
        $this->institute = $institute;

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * Set dateexam
     * 
     * @param type $dateexam
     * @return Atcertificatestude
     */
    public function setDateexam($dateexam)
    {
        $this->dateexam = $dateexam;

        return $this;
    }

    /**
     * Get dateexam
     *
     * @return string 
     */
    public function getDateexam()
    {
        return $this->dateexam;
    }

    /**
     * 
     * @param string $subject
     * @return Atcertificatestude
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * Set certificate
     *
     * @param \att\attBundle\Entity\Atcertificado $certificate
     * @return Atcertificatestude
     */
    public function setCertificado(\att\attBundle\Entity\Atcertificate $certificate = null)
    {
        $this->certificate = $certificate;

        return $this;
    }

    /**
     * Get certificate
     *
     * @return \att\attBundle\Entity\Atcertificate
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set certificate
     *
     * @param \att\attBundle\Entity\Atcertificate $certificate
     *
     * @return Atcertificatestude
     */
    public function setCertificate(\att\attBundle\Entity\Atcertificate $certificate = null)
    {
        $this->certificate = $certificate;

        return $this;
    }
}
