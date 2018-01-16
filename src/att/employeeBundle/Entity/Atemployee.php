<?php

namespace att\employeeBundle\Entity;

use att\employeeBundle\Entity\Atcontract;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use libphonenumber\PhoneNumber;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * Atemployee
 *
 * @ORM\Table(name="emp_employee", 
 *  uniqueConstraints={
 *          @ORM\UniqueConstraint(name="UQ_AtEmpleado_DNI", columns={"DNI"}), 
 *          }, 
 *  indexes={
 *         
 * })
 * 
 * @ORM\Entity(repositoryClass="att\employeeBundle\Repository\AtemployeeRepository") * 
 * @UniqueEntity(
 *      fields={"dni"},
 *      message="This Id already exists for this Employee",
 *      errorPath="dni",
 *      
 * )
 * 
 */
class Atemployee {

    /**
     * @var integer
     *
     * @ORM\Column(name="DNI", type="integer", nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\d+/",
     *     message="Person Id can contain only numbers"
     * )
     * @Assert\Length(
     *      min = 6,
     *      max = 8,
     *      minMessage = "Id  must be at least {{ limit }} numbers long",
     *      maxMessage = "Id cannot be longer than {{ limit }} numbers"
     * )
     */
    private $dni;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u",
     *     message="Name cannot contain numbers or digits"
     * )
     * 
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u",
     *     message="Last name cannot contain numbers or digits"
     * )
     * 
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * 
     * 
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=45, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="addresslat", type="string", length=45, nullable=true)
     */
    private $addresslat;

    /**
     * @var string
     *
     * @ORM\Column(name="addresslng", type="string", length=45, nullable=true)
     */
    private $addresslng;

    /**
     * 
     *
     * @ORM\Column(name="celPhone", type="phone_number",  nullable=TRUE)
     * @Type("libphonenumber\PhoneNumber")
     * @AssertPhoneNumber(
     *      defaultRegion="AR",
     *      type="mobile"
     * )
     */
    private $celphone;

    /**
     * 
     *
     * @ORM\Column(name="phone", type="phone_number",  nullable=TRUE)
     * @Type("libphonenumber\PhoneNumber")
     * @AssertPhoneNumber(defaultRegion="AR")
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     * @Type("DateTime")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=45, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=45, nullable=true)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var blob
     *
     * @ORM\Column(name="photo", type="blob", nullable=true)
     */
    private $photo;

    /**
     * 
     * 
     * @ORM\OneToMany(targetEntity="\att\employeeBundle\Entity\Atcontract", mappedBy="employee", cascade={"persist", "remove"}, orphanRemoval=TRUE)
     * @Type("ArrayCollection<att\employeeBundle\Entity\Atcontract>")
     * @Groups({"Default","getContracts"})
     * 
     */
    private $contracts;
    
    

    public function __construct() {
        $this->contracts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dni
     *
     * @param integer $dni
     * @return Atemployee
     */
    public function setDni($dni) {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return integer 
     */
    public function getDni() {
        return $this->dni;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Atemployee
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Atemployee
     */
    public function setSurname($surname) {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Atemployee
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Atemployee
     */
    public function setAddress($address) {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Set celphone
     *
     * @param string $celphone
     * @return Atemployee
     */
    public function setCelphone($celphone) {
        $this->celphone = $celphone;

        return $this;
    }

    /**
     * Get celphone
     *
     * @return string 
     */
    public function getCelphone() {
        return $this->celphone;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Atemployee
     */
    public function setState($state) {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState() {
        return $this->state;
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
     * 
     * @return type
     */
    public function getContracts() {
        if ($this->contracts) {
            return $this->contracts->toArray();
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param Atcontract $contract
     * @return \att\employeeBundle\Entity\Atemployee
     */
    public function addcontract(Atcontract $contract) {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setEmployee($this);
        }

        return $this;
    }

    /**
     * 
     * @param Atcontract $contract
     * @return \att\employeeBundle\Entity\Atemployee
     */
    public function removecontract(Atcontract $contract) {
        if ($this->contracts->contains($contract)) {
            $this->contracts->removeElement($contract);
            $contract->setEmployee(null);
        }

        return $this;
    }

    public function getBusiness() {
        return array_map(
                function ($contract) {
            return $contract->getEmployee();
        }, $this->contracts->toArray()
        );
    }

    public function __toString() {
        return "DNI:" . $this->getDni() . " Name:" . $this->getName() . " Surname:" . $this->getSurname();
    }

    /**
     * Set addresslat
     *
     * @param string $addresslat
     * @return Atemployee
     */
    public function setAddresslat($addresslat) {
        $this->addresslat = $addresslat;

        return $this;
    }

    /**
     * Get addresslat
     *
     * @return string 
     */
    public function getAddresslat() {
        return $this->addresslat;
    }

    /**
     * Set addresslng
     *
     * @param string $addresslng
     * @return Atemployee
     */
    public function setAddresslng($addresslng) {
        $this->addresslng = $addresslng;

        return $this;
    }

    /**
     * Get addresslng
     *
     * @return string 
     */
    public function getAddresslng() {
        return $this->addresslng;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Atemployee
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return Atemployee
     */
    public function setBirthday($birthday) {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday() {
        return $this->birthday;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return Atemployee
     */
    public function setSex($sex) {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Atemployee
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }


    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Atemployee
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
