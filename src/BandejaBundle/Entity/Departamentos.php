<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="ID_DEPARTAMENTO", type="integer", nullable=false)
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuarios", inversedBy="fkDepto")
     * @ORM\JoinTable(name="dep_usu",
     *   joinColumns={
     *     @ORM\JoinColumn(name="FK_ID_DEPTO", referencedColumnName="ID_DEPARTAMENTO")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="FK_ID_USUARIO", referencedColumnName="ID_USUARIO")
     *   }
     * )
     */
    private $fkUsuario;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fkUsuario = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add fkUsuario
     *
     * @param \BandejaBundle\Entity\Usuarios $fkUsuario
     *
     * @return Departamentos
     */
    public function addFkUsuario(\BandejaBundle\Entity\Usuarios $fkUsuario)
    {
        $this->fkUsuario[] = $fkUsuario;

        return $this;
    }

    /**
     * Remove fkUsuario
     *
     * @param \BandejaBundle\Entity\Usuarios $fkUsuario
     */
    public function removeFkUsuario(\BandejaBundle\Entity\Usuarios $fkUsuario)
    {
        $this->fkUsuario->removeElement($fkUsuario);
    }

    /**
     * Get fkUsuario
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFkUsuario()
    {
        return $this->fkUsuario;
    }
}
