<?php

namespace att\attBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Atcertificatemedical
 *
 * @ORM\Table(name="att_certificate_medical", indexes={@ORM\Index(name="IXFK_AtCertificateMedical_AtCertificate", columns={"certificate"})})
 * @ORM\Entity
 */
class Atcertificatemedical
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
     * @var integer
     *
     * @ORM\Column(name="matricula", type="bigint", nullable=false)
     */
    private $matricula;

    /**
     * @var string
     *
     * @ORM\Column(name="diagnostico", type="text", length=65535, nullable=true)
     */
    private $diagnostico;

    /**
     * @var boolean
     *
     * @ORM\Column(name="indicaReposo", type="boolean", nullable=true)
     */
    private $indicareposo;

    /**
     * @var \Atcertificado
     *
     * @ORM\OneToOne(targetEntity="Atcertificate", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="certificate", referencedColumnName="id")
     * @Assert\Valid
     */
    private $certificate;

        /**
     * @var string
     *
     * @ORM\Column(name="profesional", type="text", length=65535, nullable=true)
     */
    private $profesional;
    
    
    

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
     * Set matricula
     *
     * @param integer $matricula
     * @return Atcertificatemedical
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return integer 
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set diagnostico
     *
     * @param string $diagnostico
     * @return Atcertificatemedical
     */
    public function setDiagnostico($diagnostico)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico
     *
     * @return string 
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set indicareposo
     *
     * @param boolean $indicareposo
     * @return Atcertificatemedical
     */
    public function setIndicareposo($indicareposo)
    {
        $this->indicareposo = $indicareposo;

        return $this;
    }

    /**
     * Get indicareposo
     *
     * @return boolean 
     */
    public function getIndicareposo()
    {
        return $this->indicareposo;
    }

    /**
     * Set certificate
     *
     * @param \att\attBundle\Entity\Atcertificado $certificate
     * @return Atcertificatemedical
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
     * Set profesional
     *
     * @param string $profesional
     * @return Atcertificatemedical
     */
    public function setProfesional($profesional)
    {
        $this->profesional = $profesional;

        return $this;
    }

    /**
     * Get profesional
     *
     * @return string 
     */
    public function getProfesional()
    {
        return $this->profesional;
    }
    

    /**
     * Set certificate
     *
     * @param \att\attBundle\Entity\Atcertificate $certificate
     *
     * @return Atcertificatemedical
     */
    public function setCertificate(\att\attBundle\Entity\Atcertificate $certificate = null)
    {
        $this->certificate = $certificate;

        return $this;
    }
}
