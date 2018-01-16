<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(
 *              name="att_workflow_msg",
 *              indexes=
 *                      {
 *                          @ORM\Index(name="FK_workflow_idx", columns={"workflow"})
 *                      }
 *            ) 
 * 
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */

class Atworkflowmsg
{
    
       
    
    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", length=65535, nullable=true)
     */
    private $details;

    /**
     * @var string
     *
     * @ORM\Column(name="wfstate", type="string", length=50 , nullable=true)
     */
    private $wfstate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime", nullable=true)
     */
    private $createAt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50 , nullable=true)
     */
    private $username;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \att\attBundle\Entity\Atworkflow
     *
     * @ORM\ManyToOne(targetEntity="\att\attBundle\Entity\Atworkflow")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="workFlow", referencedColumnName="id")
     * })
     */
    private $workflow;




    /**
     * Set wfstate
     *
     * @param string $wfstate
     * @return Atworkflow
     */
    public function setWfstate($wfstate)
    {
        $this->wfstate = $wfstate;

        return $this;
    }

    /**
     * Get wfstate
     *
     * @return string 
     */
    public function getWfstate()
    {
        return $this->wfstate;
    }

    /**
     * Set details
     * 
     * @param string $details
     * @return \Atworkflowmsg 
     */
    public function setDetails($details) {
        $this->details = $details;
        
        return $this;
        
    }
    
    /**
     * Get details
     * 
     * @return string
     */
    public function getDetails() {
        return $this->details;
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
     * @param \att\attBundle\Entity\Atworkflow $workflow
     * @return Atworkflow
     */
    public function setWorkflow(\att\attBundle\Entity\Atworkflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \att\attBundle\Entity\Atworkflow
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
     * Set username
     *
     * @param string $username
     *
     * @return Atworkflowmsg
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
