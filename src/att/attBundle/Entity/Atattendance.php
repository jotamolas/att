<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Atattendance
 *
 * @ORM\Table(
 *  name="att_attendance", 
 *  indexes=
 *          {
 *              @ORM\Index(name="IXFK_AtAsistencia_AtEstadoEmpleado", columns={"stateAttendance"}),
 *              @ORM\Index(name="plan_idx", columns={"plan"})
 *          },
 *  uniqueConstraints=
 *          {
 *              @ORM\UniqueConstraint(name="plan_UNIQUE", columns={"plan"})
 *          }      
 *)
 * 
 * @ORM\Entity(repositoryClass="att\attBundle\Repository\AtattendanceRepository")
 * @UniqueEntity("plan")
 */
class Atattendance
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inatt", type="datetime", nullable=true)
     */
    private $inatt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="outatt", type="datetime", nullable=true)
     */
    private $outatt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logIn", type="datetime", nullable=true)
     */
    private $login;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="logOut", type="datetime", nullable=true)
     */
    private $logout;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hsWorked", type="time", nullable=true)
     */
    private $hsworked;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Atstateatt
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atstateatt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stateAttendance", referencedColumnName="id")
     * })
     */
    private $stateattendance;

    /**
     * @var \att\attBundle\Entity\Atplan
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atplan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan", referencedColumnName="id")
     * })
     */
    private $plan;



    /**
     * Set in
     *
     * @param \DateTime $inatt
     * @return Atattendance
     */
    public function setInatt($in)
    {
        $this->inatt = $in;

        return $this;
    }

    /**
     * Get in
     *
     * @return \DateTime 
     */
    public function getInatt()
    {
        return $this->inatt;
    }

    /**
     * Set out
     *
     * @param \DateTime $outatt
     * @return Atattendance
     */
    public function setOutatt($out)
    {
        $this->outatt = $out;

        return $this;
    }

    /**
     * Get out
     *
     * @return \DateTime 
     */
    public function getOutatt()
    {
        return $this->outatt;
    }

    /**
     * Set login
     *
     * @param \DateTime $login
     * @return Atattendance
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return \DateTime 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set logout
     *
     * @param \DateTime $logout
     * @return Atattendance
     */
    public function setLogout($logout)
    {
        $this->logout = $logout;

        return $this;
    }

    /**
     * Get logout
     *
     * @return \DateTime 
     */
    public function getLogout()
    {
        return $this->logout;
    }

    /**
     * Set hsworked
     *
     * @param \DateTime $hsworked
     * @return Atattendance
     */
    public function setHsworked($hsworked)
    {
        $this->hsworked = $hsworked;

        return $this;
    }

    /**
     * Get hsworked
     *
     * @return \DateTime 
     */
    public function getHsworked()
    {
        return $this->hsworked;
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
     * Set stateattendance
     *
     * @param \att\attBundle\Entity\Atstateatt $stateattendance
     * @return Atattendance
     */
    public function setStateattendance(\att\attBundle\Entity\Atstateatt $stateattendance = null)
    {
        $this->stateattendance = $stateattendance;

        return $this;
    }

    /**
     * Get stateattendance
     *
     * @return \att\attBundle\Entity\Atstateatt 
     */
    public function getStateattendance()
    {
        return $this->stateattendance;
    }

    /**
     * Set plan
     *
     * @param \att\attBundle\Entity\Atplan $plan
     * @return Atattendance
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
}
