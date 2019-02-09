<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personas
 *
 * @ORM\Table(name="personas", indexes={@ORM\Index(name="rut", columns={"rut"}), @ORM\Index(name="vrut", columns={"vrut"})})
 * @ORM\Entity
 */
class Personas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rut", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $rut;

    /**
     * @var string
     *
     * @ORM\Column(name="vrut", type="string", length=1, nullable=false)
     */
    private $vrut;

    /**
     * @var string
     *
     * @ORM\Column(name="nombres", type="string", length=255, nullable=false)
     */
    private $nombres;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidopaterno", type="string", length=255, nullable=false)
     */
    private $apellidopaterno;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidomaterno", type="string", length=255, nullable=false)
     */
    private $apellidomaterno;



    /**
     * Get rut
     *
     * @return integer
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Set vrut
     *
     * @param string $vrut
     *
     * @return Personas
     */
    public function setVrut($vrut)
    {
        $this->vrut = $vrut;

        return $this;
    }

    /**
     * Get vrut
     *
     * @return string
     */
    public function getVrut()
    {
        return $this->vrut;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     *
     * @return Personas
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellidopaterno
     *
     * @param string $apellidopaterno
     *
     * @return Personas
     */
    public function setApellidopaterno($apellidopaterno)
    {
        $this->apellidopaterno = $apellidopaterno;

        return $this;
    }

    /**
     * Get apellidopaterno
     *
     * @return string
     */
    public function getApellidopaterno()
    {
        return $this->apellidopaterno;
    }

    /**
     * Set apellidomaterno
     *
     * @param string $apellidomaterno
     *
     * @return Personas
     */
    public function setApellidomaterno($apellidomaterno)
    {
        $this->apellidomaterno = $apellidomaterno;

        return $this;
    }

    /**
     * Get apellidomaterno
     *
     * @return string
     */
    public function getApellidomaterno()
    {
        return $this->apellidomaterno;
    }
}
