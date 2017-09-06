<?php

namespace att\employeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Atcontract
 * @ORM\Table(name="emp_contract",
 *      indexes={
 *          @ORM\Index(name="IDX_6316C3561CD9BF62", columns={"employee"}),
 *          @ORM\Index(name="IDX_6316C3563847FA69", columns={"business"}),
 *          @ORM\Index(name="AtContract_ibfk_3", columns={"status"})
 *      }
 *     )
 * 
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtcontractRepository")
 * 
 * @UniqueEntity(
 *      fields={"employee","business"},
 *      message="This Employee just added to this Business",
 *      errorPath="employee",
 *      groups={"Default", "StepForm2"})
 * 
 */
class Atcontract implements UserInterface {

    /**
     * @var string 
     * 
     */
    private $username;


    /**
     * @var string 
     * @Groups({"getAgreement"})
     */
    private $password;

    /**
     * @var boolean
     * @Groups({"getAgreement"})
     */
    private $isActive;

    /**
     * @ORM\Id
     * @var \att\employeeBundle\Entity\Atemployee
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atemployee", inversedBy="contracts",cascade={"persist"})
     * @ORM\JoinColumn(name="employee", referencedColumnName="id", nullable=false)
     * @Type("att\employeeBundle\Entity\Atemployee")
     * @Groups({"getEmployee"})
     * @Assert\NotBlank(groups={"Default", "StepForm1"})
     * 
     */
    private $employee;

    /**
     * @ORM\Id
     * @var \att\employeeBundle\Entity\Atbusiness
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atbusiness", inversedBy="contracts")
     * @ORM\JoinColumn(name="business", referencedColumnName="id", nullable=false)
     * @Type("att\employeeBundle\Entity\Atbusiness")
     * @Groups({"getBusiness"})
     * @Assert\NotBlank(groups={"Default", "StepForm2"})
     * 
     */
    private $business;

    /**
     * 
     * @var \att\employeeBundle\Entity\Atemployeestatus
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atemployeestatus")
     * @ORM\JoinColumn(name="status", referencedColumnName="id", nullable=false)
     * @Type("att\employeeBundle\Entity\Atemployeestatus")
     * @Groups({"getStatus"})
     * 
     */
    private $status;

    /**
     * 
     * @var \att\employeeBundle\Entity\Atschema
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atschema")
     * @ORM\JoinColumn(name="sch", referencedColumnName="id", nullable=false)
     * @Type("att\employeeBundle\Entity\Atschema")
     * @Groups({"getSchema"})
     * 
     */
    private $schema;

    /**
     * @var integer 
     * @ORM\Column(name="fileNumber", type="integer", nullable=true)
     * 
     */
    private $file_number;

    /**
     * @var \att\employeeBundle\Entity\Atagreement
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atagreement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agreement", referencedColumnName="id")
     * })
     * @Type("att\employeeBundle\Entity\Atagreement")
     * @Groups({"getAgreement"})
     */
    private $agreement;
    
    /**
     * @var \att\employeeBundle\Entity\Atdepartment
     *
     * @ORM\ManyToOne(targetEntity="\att\employeeBundle\Entity\Atdepartment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="department", referencedColumnName="id")
     * })
     * @Type("att\employeeBundle\Entity\Atdepartment")
     * 
     */
    private $department;
    
    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=true)
     * @Type("DateTime")
     */
    private $start_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=true)
     * @Type("DateTime")
     */
    private $end_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="in_time", type="time", nullable=true)
     * @Type("DateTime")
     */
    private $in_time;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="out_time", type="time", nullable=true)
     * @Type("DateTime")
     * 
     */
    private $out_time;

    /**
     * @var integer
     * 
     * @ORM\Column(name="id", type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="att\employeeBundle\Entity\Atrestday", inversedBy="contracts", fetch="EAGER")
     * @ORM\JoinTable(name="AtContractRestDay",
     *            joinColumns=
     *              {
     *                  @ORM\JoinColumn(name="employee_id", referencedColumnName="employee"),
     *                  @ORM\JoinColumn(name="business_id", referencedColumnName="business")
     *              },
     *            inverseJoinColumns=
     *              {
     *                  @ORM\JoinColumn(name="restday_id", referencedColumnName="id")
     *              }
     *       )
     * 
     * 
     * @Type("ArrayCollection<att\employeeBundle\Entity\Atrestday>")
     * @Groups({"getRestDays"})
     */
    private $restDays;

    public function __construct() {
        $this->restDays = new \Doctrine\Common\Collections\ArrayCollection();
        $this->getEndDate() ? $this->isActive = TRUE : $this->isActive = FALSE;
        $this->username = $this->getFileNumber();
    }

    /**
     * 
     * @return type
     */
    public function getUsername() {
        return $this->getFileNumber();
    }

    public function getRoles() {
        return ['ROLE_FRONTEND'];
    }

    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return 'holis';
    }

    public function getSalt() {
        return NULL;
    }

    public function equals(UserInterface $user) {
        return $this->username === $user->getUsername();
    }

    /**
     * Set employee
     *
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @return Atcontract
     */
    public function setEmployee(\att\employeeBundle\Entity\Atemployee $employee) {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \att\employeeBundle\Entity\Atemployee 
     */
    public function getEmployee() {
        return $this->employee;
    }

    /**
     * Set business
     *
     * @param \att\employeeBundle\Entity\Atbusiness $business
     * @return Atcontract
     */
    public function setBusiness(\att\employeeBundle\Entity\Atbusiness $business) {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return \att\employeeBundle\Entity\Atbusiness 
     */
    public function getBusiness() {
        return $this->business;
    }

    /**
     * Set state
     *
     * @param \att\employeeBundle\Entity\Atemployeestatus $status
     * @return Atcontract
     */
    public function setStatus(\att\employeeBundle\Entity\Atemployeestatus $status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \att\employeeBundle\Entity\Atemployeestatus 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set schema
     *
     * @param \att\employeeBundle\Entity\Atschema $schema
     * @return Atcontract
     */
    public function setSchema(\att\employeeBundle\Entity\Atschema $schema = null) {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get schema
     *
     * @return \att\employeeBundle\Entity\Atschema
     */
    public function getSchema() {
        return $this->schema;
    }

    /**
     * Set file_number
     *
     * @param integer $fileNumber
     * @return Atcontract
     */
    public function setFileNumber($fileNumber) {
        $this->file_number = $fileNumber;

        return $this;
    }

    /**
     * Get file_number
     *
     * @return integer 
     */
    public function getFileNumber() {
        return $this->file_number;
    }

    /**
     * Set agreement
     *
     * @param \att\employeeBundle\Entity\Atagreement $agreement
     * @return Atcontract
     */
    public function setAgreement(\att\employeeBundle\Entity\Atagreement $agreement = null) {
        $this->agreement = $agreement;

        return $this;
    }

    /**
     * Get agreement
     *
     * @return \att\employeeBundle\Entity\Atagreement 
     */
    public function getAgreement() {
        return $this->agreement;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return Atcontract
     */
    public function setStartDate($startDate) {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate() {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     * @return Atcontract
     */
    public function setEndDate($endDate) {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate() {
        return $this->end_date;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Atcontract
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Set in_time
     *
     * @param \DateTime $inTime
     * @return Atcontract
     */
    public function setInTime($inTime) {
        $this->in_time = $inTime;

        return $this;
    }

    /**
     * Get in_time
     *
     * @return \DateTime 
     */
    public function getInTime() {
        return $this->in_time;
    }

    /**
     * Set out_time
     *
     * @param \DateTime $outTime
     * @return Atcontract
     */
    public function setOutTime($outTime) {
        $this->out_time = $outTime;

        return $this;
    }

    /**
     * Get out_time
     *
     * @return \DateTime 
     */
    public function getOutTime() {
        return $this->out_time;
    }

    /**
     * 
     * @param \att\employeeBundle\Entity\Atrestday $restday
     */
    public function addRestDay(Atrestday $restday) {
        if ($this->restDays->contains($restday)) {
            return;
        }
        $restday->addContract($this); // synchronously updating inverse side
        $this->restDays[] = $restday;
    }

    /**
     * 
     * @return type
     */
    public function getRestDays() {
        return $this->restDays;
    }

    /**
     * 
     * @param \att\employeeBundle\Entity\Atrestday $restday
     */
    public function setRestDays(\Doctrine\Common\Collections\ArrayCollection $restdays) {

        foreach ($restdays as $restday) {
            $this->addRestDay($restday);
        }

        return $this;
    }

    public function __toString() {
        return " FileNumber :" . $this->getFileNumber() . " Business:" . $this->getBusiness()->getDescription() . " Status:" .
                $this->getStatus()->getDescription();
    }


    /**
     * Set department
     *
     * @param \att\employeeBundle\Entity\Atdepartment $department
     *
     * @return Atcontract
     */
    public function setDepartment(\att\employeeBundle\Entity\Atdepartment $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \att\employeeBundle\Entity\Atdepartment
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Remove restDay
     *
     * @param \att\employeeBundle\Entity\Atrestday $restDay
     */
    public function removeRestDay(\att\employeeBundle\Entity\Atrestday $restDay)
    {
        $this->restDays->removeElement($restDay);
    }
}
