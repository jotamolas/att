<?php

namespace att\appBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttConfiguration
 *
 * @ORM\Table(name="att_configuration")
 * @ORM\Entity(repositoryClass="att\appBundle\Repository\AttConfigurationRepository")
 */
class AttConfiguration {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="parameter", type="string", length=255)
     */
    private $parameter;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="module", type="string", length=255)
     */
    private $module;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set parameter
     *
     * @param string $parameter
     *
     * @return AttConfiguration
     */
    public function setParameter($parameter) {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get parameter
     *
     * @return string
     */
    public function getParameter() {
        return $this->parameter;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AttConfiguration
     */
    public function setValue($value) {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue() {
        return $this->value;
    }


    /**
     * Set module
     *
     * @param string $module
     *
     * @return AttConfiguration
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }
}
