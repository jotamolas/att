<?php

namespace att\attBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Atworkflowtype
 *
 * @ORM\Table(name="att_workflow_type", indexes={@ORM\Index(name="name", columns={"name"}), @ORM\Index(name="IXFK_AtWorkflowState_AtWorkflow", columns={"name"})})
 * @ORM\Entity
 */
class Atworkflowtype
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="entityWorkFlow", type="string", length=50, nullable=false)
     */
    private $entityworkflow;

    /**
     * @var string
     *
     * @ORM\Column(name="serviceID", type="string", length=50)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $serviceid;



    /**
     * Set name
     *
     * @param string $name
     * @return Atworkflowtype
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set entityworkflow
     *
     * @param string $entityworkflow
     * @return Atworkflowtype
     */
    public function setEntityworkflow($entityworkflow)
    {
        $this->entityworkflow = $entityworkflow;

        return $this;
    }

    /**
     * Get entityworkflow
     *
     * @return string 
     */
    public function getEntityworkflow()
    {
        return $this->entityworkflow;
    }

    /**
     * Get serviceid
     *
     * @return string 
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
}
