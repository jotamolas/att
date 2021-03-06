<?php

namespace att\syncBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExternalDatabase
 *
 * @ORM\Table(name="sync_external_database")
 * @ORM\Entity(repositoryClass="att\syncBundle\Repository\ExternalDatabaseRepository")
 */
class ExternalDatabase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \att\syncBundle\Entity\Atexternsystem
     *
     * @ORM\ManyToOne(targetEntity="\att\syncBundle\Entity\Atexternsystem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="system", referencedColumnName="id")
     * })
     */
    private $system;

    /**
     * @var string
     *
     * @ORM\Column(name="database_name", type="string", length=255)
     */
    private $databaseName;

    /**
     * @var string
     *
     * @ORM\Column(name="database_user", type="string", length=255)
     */
    private $databaseUser;

    /**
     * @var string
     *
     * @ORM\Column(name="database_user_password", type="string", length=255)
     */
    private $databaseUserPassword;
    
    /**
     * @var string
     *
     * @ORM\Column(name="database_connection_string", type="string", length=255)
     */
    private $databaseConnectionString;

    /**
     * @var \att\syncBundle\Entity\ExternalDatabaseType
     *
     * @ORM\ManyToOne(targetEntity="\att\syncBundle\Entity\ExternalDatabaseType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set databaseName
     *
     * @param string $databaseName
     *
     * @return ExternalDatabase
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;

        return $this;
    }

    /**
     * Get databaseName
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * Set databaseUser
     *
     * @param string $databaseUser
     *
     * @return ExternalDatabase
     */
    public function setDatabaseUser($databaseUser)
    {
        $this->databaseUser = $databaseUser;

        return $this;
    }

    /**
     * Get databaseUser
     *
     * @return string
     */
    public function getDatabaseUser()
    {
        return $this->databaseUser;
    }

    /**
     * Set databaseUserPassword
     *
     * @param string $databaseUserPassword
     *
     * @return ExternalDatabase
     */
    public function setDatabaseUserPassword($databaseUserPassword)
    {
        $this->databaseUserPassword = $databaseUserPassword;

        return $this;
    }

    /**
     * Get databaseUserPassword
     *
     * @return string
     */
    public function getDatabaseUserPassword()
    {
        return $this->databaseUserPassword;
    }



    /**
     * Set system
     *
     * @param \att\syncBundle\Entity\Atexternsystem $system
     *
     * @return ExternalDatabase
     */
    public function setSystem(\att\syncBundle\Entity\Atexternsystem $system = null)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return \att\syncBundle\Entity\Atexternsystem
     */
    public function getSystem()
    {
        return $this->system;
    }



    /**
     * Set databaseConnectionString
     *
     * @param string $databaseConnectionString
     *
     * @return ExternalDatabase
     */
    public function setDatabaseConnectionString($databaseConnectionString)
    {
        $this->databaseConnectionString = $databaseConnectionString;

        return $this;
    }

    /**
     * Get databaseConnectionString
     *
     * @return string
     */
    public function getDatabaseConnectionString()
    {
        return $this->databaseConnectionString;
    }

    /**
     * Set type
     *
     * @param \att\syncBundle\Entity\ExternalDatabaseType $type
     *
     * @return ExternalDatabase
     */
    public function setType(\att\syncBundle\Entity\ExternalDatabaseType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \att\syncBundle\Entity\ExternalDatabaseType
     */
    public function getType()
    {
        return $this->type;
    }
}
