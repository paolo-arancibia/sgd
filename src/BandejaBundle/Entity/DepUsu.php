<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DepUsu
 *
 * @ORM\Table(name="dep_usu", indexes={@ORM\Index(name="IDX_A657D367B0349E59", columns={"FK_ID_DEPTO"}), @ORM\Index(name="IDX_A657D36755303CDA", columns={"FK_ID_USUARIO"})})
 * @ORM\Entity
 */
class DepUsu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_DEP_USU", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDepUsu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ENCARGADO", type="boolean", nullable=false)
     */
    private $encargado = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="CARGO", type="string", length=255, nullable=true)
     */
    private $cargo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_C", type="datetime", nullable=false)
     */
    private $fechaC;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_M", type="datetime", nullable=false)
     */
    private $fechaM;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_E", type="datetime", nullable=true)
     */
    private $fechaE;

    /**
     * @var \BandejaBundle\Entity\Usuarios
     *
     * @ORM\ManyToOne(targetEntity="BandejaBundle\Entity\Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_USUARIO", referencedColumnName="ID_USUARIO")
     * })
     */
    private $fkUsuario;

    /**
     * @var \BandejaBundle\Entity\Departamentos
     *
     * @ORM\ManyToOne(targetEntity="BandejaBundle\Entity\Departamentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_DEPTO", referencedColumnName="ID_DEPARTAMENTO")
     * })
     */
    private $fkDepto;



    /**
     * Get idDepUsu
     *
     * @return integer
     */
    public function getIdDepUsu()
    {
        return $this->idDepUsu;
    }

    /**
     * Set encargado
     *
     * @param boolean $encargado
     *
     * @return DepUsu
     */
    public function setEncargado($encargado)
    {
        $this->encargado = $encargado;

        return $this;
    }

    /**
     * Get encargado
     *
     * @return boolean
     */
    public function getEncargado()
    {
        return $this->encargado;
    }

    /**
     * Set cargo
     *
     * @param string $cargo
     *
     * @return DepUsu
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return DepUsu
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
     * @return DepUsu
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
     * @return DepUsu
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
     * Set fkUsuario
     *
     * @param \BandejaBundle\Entity\Usuarios $fkUsuario
     *
     * @return DepUsu
     */
    public function setFkUsuario(\BandejaBundle\Entity\Usuarios $fkUsuario = null)
    {
        $this->fkUsuario = $fkUsuario;

        return $this;
    }

    /**
     * Get fkUsuario
     *
     * @return \BandejaBundle\Entity\Usuarios
     */
    public function getFkUsuario()
    {
        return $this->fkUsuario;
    }

    /**
     * Set fkDepto
     *
     * @param \BandejaBundle\Entity\Departamentos $fkDepto
     *
     * @return DepUsu
     */
    public function setFkDepto(\BandejaBundle\Entity\Departamentos $fkDepto = null)
    {
        $this->fkDepto = $fkDepto;

        return $this;
    }

    /**
     * Get fkDepto
     *
     * @return \BandejaBundle\Entity\Departamentos
     */
    public function getFkDepto()
    {
        return $this->fkDepto;
    }
}
