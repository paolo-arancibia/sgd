<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * Departamentos
 *
 * @ORM\Table(name="DEPARTAMENTOS")
 * @ORM\Entity
 */
class Departamentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_DEPARTAMENTO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDepartamento;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="DIRECCION", type="string", length=255, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ABREV", type="string", length=10, nullable=true)
     */
    private $abrev;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_C", type="datetime", nullable=true)
     */
    private $fechaC;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_M", type="datetime", nullable=true)
     */
    private $fechaM;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_E", type="datetime", nullable=true)
     */
    private $fechaE;

    /**
     * @var \Collection
     *
     * @ORM\OneToMay(targetEntity="BandejaBundle\Entity\DepUsu", mappedBy="fkDepto")
     */
    private $depUsus;


    /**
     * Constructor
     *
     *
     */
    public function __contruct()
    {
        $this->depUsus = new Collection();
    }

    /**
     * Get idDepartamento
     *
     * @return integer
     */
    public function getIdDepartamento()
    {
        return $this->idDepartamento;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Departamentos
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Departamentos
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set abrev
     *
     * @param string $abrev
     *
     * @return Departamentos
     */
    public function setAbrev($abrev)
    {
        $this->abrev = $abrev;

        return $this;
    }

    /**
     * Get abrev
     *
     * @return string
     */
    public function getAbrev()
    {
        return $this->abrev;
    }

    /**
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return Departamentos
     */
    public function setFechaC($fechaC)
    {
        $this->fechaC = $fechaC;

        return $this;
    }

    /**
     * Get fechaC
     *
     * @return \DateTime
     */
    public function getFechaC()
    {
        return $this->fechaC;
    }

    /**
     * Set fechaM
     *
     * @param \DateTime $fechaM
     *
     * @return Departamentos
     */
    public function setFechaM($fechaM)
    {
        $this->fechaM = $fechaM;

        return $this;
    }

    /**
     * Get fechaM
     *
     * @return \DateTime
     */
    public function getFechaM()
    {
        return $this->fechaM;
    }

    /**
     * Set fechaE
     *
     * @param \DateTime $fechaE
     *
     * @return Departamentos
     */
    public function setFechaE($fechaE)
    {
        $this->fechaE = $fechaE;

        return $this;
    }

    /**
     * Get fechaE
     *
     * @return \DateTime
     */
    public function getFechaE()
    {
        return $this->fechaE;
    }

    /**
     * Get depUsus
     *
     * @return \Collection
     */
    public function getDepUsus()
    {
        return $this->depUsus;
    }

    /**
     * Add a DepUsu to depUsus
     *
     * @param \DepUsu $depUsu
     *
     * @return Departamentos
     */
    public function addDepUsus($depUsu)
    {
        if( $this->depUsus->contains($depUsu) )
        {
            return;
        }

        $this->depUsus->add($depUsu);

        return $this;
    }

    /**
     * Remove a DepUsu to depUsus
     *
     * @param \DepUsu $depUsu
     *
     * @return Departamentos
     */
    public function removeDepUsus($depUsu)
    {
        if( ! $this->depUsus->contains($depUsu) )
        {
            return;
        }

        $this->depUsus->removeElement($depUsu);

        return $this;
    }
}
