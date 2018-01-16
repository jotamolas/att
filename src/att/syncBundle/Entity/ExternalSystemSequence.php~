<?php

namespace att\syncBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExternalSystemSequence
 *
 * @ORM\Table(name="sync_external_system_sequence")
 * @ORM\Entity(repositoryClass="att\syncBundle\Repository\ExternalSystemSequenceRepository")
 */
class ExternalSystemSequence
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
     * @var int
     *
     * @ORM\Column(name="last", type="integer")
     */
    private $last;

    /**
     * @var int
     *
     * @ORM\Column(name="next", type="integer")
     */
    private $next;

    /**
    * @var datetime
    *
    * @ORM\Column(name="last_date_connection", type="datetime")
    */
    private $lastDateConnection;

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
     * Set last
     *
     * @param integer $last
     *
     * @return ExternalSystemSequence
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }

    /**
     * Get last
     *
     * @return int
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * Set next
     *
     * @param integer $next
     *
     * @return ExternalSystemSequence
     */
    public function setNext($next)
    {
        $this->next = $next;

        return $this;
    }

    /**
     * Get next
     *
     * @return int
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * Set lastDateConnection
     *
     * @param \DateTime $lastDateConnection
     *
     * @return ExternalSystemSequence
     */
    public function setLastDateConnection($lastDateConnection)
    {
        $this->lastDateConnection = $lastDateConnection;

        return $this;
    }

    /**
     * Get lastDateConnection
     *
     * @return \DateTime
     */
    public function getLastDateConnection()
    {
        return $this->lastDateConnection;
    }

    /**
     * Set system
     *
     * @param \att\syncBundle\Entity\Atexternsystem $system
     *
     * @return ExternalSystemSequence
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
}
