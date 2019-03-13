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
     * @ORM\Column(name="rut", type="integer")
     * @ORM\Id
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
     * @var string
     *
     * @ORM\Column(name="nombre_calle", type="string", length=255, nullable=false)
     */
    private $nombre_calle;

    /**
     * @var integer
     *
     * @ORM\Column(name="numdirec", type="integer", length=11, nullable=false)
     */
    private $numdirec;

    /**
     * @var string
     *
     * @ORM\Column(name="referenciadir", type="string", length=255, nullable=false)
     */
    private $referenciadir;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_comuna", type="string", length=255, nullable=false)
     */
    private $nombre_comuna;

    /**
     * @var integer
     *
     * @ORM\Column(name="fono", type="integer", length=11, nullable=false)
     */
    private $fono;

    /**
     * @var integer
     *
     * @ORM\Column(name="fono_2", type="integer", length=11, nullable=false)
     */
    private $fono_2;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=false)
     */
    private $fecha_nacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="sexo", type="string", length=255, nullable=false)
     */
    private $sexo;

    /**
     * @var string
     *
     * @ORM\Column(name="unidad_v", type="string", length=255, nullable=false)
     */
    private $unidad_v;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_reg", type="datetime", nullable=false)
     */
    private $fecha_reg;



    /**
     * Set rut
     *
     * @param integer $rut
     *
     * @return Personas
     */
    public function setRut($rut)
    {
        $this->rut = $rut;

        return $this;
    }

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

    /**
     * Get Nombre Completo
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombres . ' ' . $this->apellidopaterno . ' ' . $this->apellidomaterno;
    }

    /**
     * Set nombre_calle
     *
     * @param string $nombre_calle
     *
     * @return Personas
     */
    public function setNombreCalle($nombre_calle)
    {
        $this->nombre_calle = $nombre_calle;

        return $this;
    }

    /**
     * Get nombre_calle
     *
     * @return string
     */
    public function getNombreCalle()
    {
        return $this->nombre_calle;
    }

    /**
     * Set numdirec
     *
     * @param integer $numdirec
     *
     * @return Personas
     */
    public function setNumdirec($numdirec)
    {
        $this->numdirec = $numdirec;

        return $this;
    }

    /**
     * Get numdirec
     *
     * @return integer
     */
    public function getNumdirec()
    {
        return $this->numdirec;
    }

    /**
     * Set referenciadir
     *
     * @param string $referenciadir
     *
     * @return Personas
     */
    public function setReferenciadir($referenciadir)
    {
        $this->referenciadir = $referenciadir;

        return $this;
    }

    /**
     * Get referenciadir
     *
     * @return string
     */
    public function getReferenciadir()
    {
        return $this->referenciadir;
    }

    /**
     * Set nombre_comuna
     *
     * @param string $nombre_comuna
     *
     * @return Personas
     */
    public function setNombreComuna($nombre_comuna)
    {
        $this->nombre_comuna = $nombre_comuna;

        return $this;
    }

    /**
     * Get nombre_comuna
     *
     * @return string
     */
    public function getNombreComuna()
    {
        return $this->nombre_comuna;
    }

    /**
     * Set fono
     *
     * @param integer $fono
     *
     * @return Personas
     */
    public function setFono($fono)
    {
        $this->fono = $fono;

        return $this;
    }

    /**
     * Get fono
     *
     * @return integer
     */
    public function getFono()
    {
        return $this->fono;
    }

    /**
     * Set fono_2
     *
     * @param integer $fono_2
     *
     * @return Personas
     */
    public function setFono2($fono_2)
    {
        $this->fono_2 = $fono_2;

        return $this;
    }

    /**
     * Get fono_2
     *
     * @return integer
     */
    public function getFono2()
    {
        return $this->fono_2;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Personas
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fecha_nacimiento
     *
     * @param \DateTime $fecha_nacimiento
     *
     * @return Personas
     */
    public function setFechaNacimiento(\DateTime $fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }

    /**
     * Get fecha_nacimiento
     *
     * @return \Date
     */
    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }

   /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Personas
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

   /**
     * Set unidad_v
     *
     * @param string $unidad_v
     *
     * @return Personas
     */
    public function setUnidadV($unidad_v)
    {
        $this->unidad_v = $unidad_v;

        return $this;
    }

    /**
     * Get unidad_v
     *
     * @return string
     */
    public function getUnidadV()
    {
        return $this->unidad_v;
    }

   /**
    * Set fecha_reg
     *
     * @param \DateTime $fecha_reg
     *
     * @return Personas
     */
    public function setFechaReg(\DateTime $fecha_reg)
    {
        $this->fecha_reg = $fecha_reg;

        return $this;
    }

    /**
     * Get fecha_reg
     *
     * @return \DateTime
     */
    public function getFechaReg()
    {
        return $this->fecha_reg;
    }

}
