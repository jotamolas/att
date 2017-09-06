<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(
 *              name="att_workflow",
 *              indexes=
 *                      {
 *                          @ORM\Index(name="IXFK_wftype", columns={"workFlow"})
 *                      }
 *            ) 
 * @ORM\Entity(repositoryClass="att\attBundle\Repository\AtworkflowRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *          fields={"entityid", "workflow"},
 *          message="Ya existe un Tramite para la actividad seleccionada."
 *  )
 */

class Atworkflow
{
    /**
     * @var string
     *
     * @ORM\Column(name="stateKey", type="string", length=50, nullable=true)
     */
    private $statekey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="entityID", type="integer", nullable=true)
     */
    private $entityid;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Atworkflowtype
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atworkflowtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="workFlow", referencedColumnName="serviceID")
     * })
     */
    private $workflow;




    /**
     * Set statekey
     *
     * @param string $statekey
     * @return Atworkflow
     */
    public function setStatekey($statekey)
    {
        $this->statekey = $statekey;

        return $this;
    }

    /**
     * Get statekey
     *
     * @return string 
     */
    public function getStatekey()
    {
        return $this->statekey;
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
     * Set createAt
     *
     * @param \DateTime $createAt
     * @return Atworkflow
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set entityid
     *
     * @param integer $entityid
     * @return Atworkflow
     */
    public function setEntityid($entityid)
    {
        $this->entityid = $entityid;

        return $this;
    }

    /**
     * Get entityid
     *
     * @return integer 
     */
    public function getEntityid()
    {
        return $this->entityid;
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
     * Set workflow
     *
     * @param \att\attBundle\Entity\Atworkflowtype $workflow
     * @return Atworkflow
     */
    public function setWorkflow(\att\attBundle\Entity\Atworkflowtype $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \att\attBundle\Entity\Atworkflowtype 
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }
    
    
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist(){
        $this->setCreateAt(new \DateTime());
        
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(){
        $this->setModifiedAt(new \DateTime());
    }
    
    public function __toString() {
        return "WF | Id: ".$this->getId()."| Worklfow Type: ".$this->getWorkflow()->getName(). "| Entity Id:".$this->getEntityid();
    }
}
