<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atplaninconsistency
 *
 * @ORM\Table(name="att_plan_inconsistency", indexes={@ORM\Index(name="plan_idx", columns={"plan"}), @ORM\Index(name="att_idx", columns={"att"})})
 * @ORM\Entity
 */
class Atplaninconsistency
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
     * @ORM\Column(name="obs", type="string", length=45, nullable=false)
     */
    private $obs;

    /**
     * @var \Atplan
     *
     * @ORM\ManyToOne(targetEntity="Atplan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan", referencedColumnName="id")
     * })
     */
    private $plan;

    /**
     * @var \Atattendance
     *
     * @ORM\ManyToOne(targetEntity="Atattendance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="att", referencedColumnName="id")
     * })
     */
    private $att;



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
     * @return Atplaninconsistency
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

    /**
     * Set plan
     *
     * @param \att\attBundle\Entity\Atplan $plan
     * @return Atplaninconsistency
     */
    public function setPlan(\att\attBundle\Entity\Atplan $plan = null)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return \att\attBundle\Entity\Atplan 
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set att
     *
     * @param \att\attBundle\Entity\Atattendance $att
     * @return Atplaninconsistency
     */
    public function setAtt(\att\attBundle\Entity\Atattendance $att = null)
    {
        $this->att = $att;

        return $this;
    }

    /**
     * Get att
     *
     * @return \att\attBundle\Entity\Atattendance 
     */
    public function getAtt()
    {
        return $this->att;
    }
}
