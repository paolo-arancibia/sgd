<?php

namespace AccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permisos
 *
 * @ORM\Table(name="PERMISOS", indexes={@ORM\Index(name="FK_ID_USUARIO", columns={"FK_ID_USUARIO"}), @ORM\Index(name="FK_ID_APP", columns={"FK_ID_APP"}), @ORM\Index(name="FK_FK_PERFIL", columns={"FK_FK_PERFIL"})})
 * @ORM\Entity
 */
class Permisos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_PERMISO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPermiso;

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
     * @var \AccessBundle\Entity\Perfiles
     *
     * @ORM\ManyToOne(targetEntity="AccessBundle\Entity\Perfiles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_FK_PERFIL", referencedColumnName="ID_PERFIL")
     * })
     */
    private $fkFkPerfil;

    /**
     * @var \AccessBundle\Entity\Usuarios
     *
     * @ORM\ManyToOne(targetEntity="BandejaBundle\Entity\Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_USUARIO", referencedColumnName="ID_USUARIO")
     * })
     */
    private $fkUsuario;

    /**
     * @var \AccessBundle\Entity\App
     *
     * @ORM\ManyToOne(targetEntity="AccessBundle\Entity\App")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_APP", referencedColumnName="ID_APP")
     * })
     */
    private $fkApp;

    /**
     * Get idPermiso
     *
     * @return integer
     */
    public function getIdPermiso()
    {
        return $this->idPermiso;
    }

    /**
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return Permisos
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
     * @return Permisos
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
     * @return Permisos
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
     * Set fkFkPerfil
     *
     * @param \AccessBundle\Entity\Perfiles $fkFkPerfil
     *
     * @return Permisos
     */
    public function setFkFkPerfil(\AccessBundle\Entity\Perfiles $fkFkPerfil = null)
    {
        $this->fkFkPerfil = $fkFkPerfil;

        return $this;
    }

    /**
     * Get fkFkPerfil
     *
     * @return \AccessBundle\Entity\Perfiles
     */
    public function getFkFkPerfil()
    {
        return $this->fkFkPerfil;
    }

    /**
     * Set fkUsuario
     *
     * @param \BandejaBundle\Entity\Usuarios $fkUsuario
     *
     * @return Permisos
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
     * Set fkApp
     *
     * @param \AccessBundle\Entity\App $fkApp
     *
     * @return Permisos
     */
    public function setFkApp(\AccessBundle\Entity\App $fkApp = null)
    {
        $this->fkApp = $fkApp;

        return $this;
    }

    /**
     * Get fkApp
     *
     * @return \AccessBundle\Entity\App
     */
    public function getFkApp()
    {
        return $this->fkApp;
    }
}
