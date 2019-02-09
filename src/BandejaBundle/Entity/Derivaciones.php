<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Derivaciones
 *
 * @ORM\Table(name="DERIVACIONES", indexes={@ORM\Index(name="FK_ID_DOC", columns={"FK_ID_DOC"}), @ORM\Index(name="FK_ID_DEPTO", columns={"FK_ID_DEPTO"}), @ORM\Index(name="FK_ID_REMITENTE", columns={"FK_ID_REMITENTE"}), @ORM\Index(name="FK_ID_DESTINATARIO", columns={"FK_ID_DESTINATARIO"})})
 * @ORM\Entity
 */
class Derivaciones
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_DERIVACION", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDerivacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TIPO", type="boolean", nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="NOTA", type="string", length=255, nullable=true)
     */
    private $nota;

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
     * @var \Documentos
     *
     * @ORM\ManyToOne(targetEntity="Documentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_DOC", referencedColumnName="ID_DOC")
     * })
     */
    private $fkDoc;

    /**
     * @var \Departamentos
     *
     * @ORM\ManyToOne(targetEntity="Departamentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_DEPTO", referencedColumnName="ID_DEPARTAMENTO")
     * })
     */
    private $fkDepto;

    /**
     * @var \Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_REMITENTE", referencedColumnName="ID_USUARIO")
     * })
     */
    private $fkRemitente;

    /**
     * @var \Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_DESTINATARIO", referencedColumnName="ID_USUARIO")
     * })
     */
    private $fkDestinatario;



    /**
     * Get idDerivacion
     *
     * @return integer
     */
    public function getIdDerivacion()
    {
        return $this->idDerivacion;
    }

    /**
     * Set tipo
     *
     * @param boolean $tipo
     *
     * @return Derivaciones
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return boolean
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set nota
     *
     * @param string $nota
     *
     * @return Derivaciones
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get nota
     *
     * @return string
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return Derivaciones
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
     * @return Derivaciones
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
     * @return Derivaciones
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
     * Set fkDoc
     *
     * @param \BandejaBundle\Entity\Documentos $fkDoc
     *
     * @return Derivaciones
     */
    public function setFkDoc(\BandejaBundle\Entity\Documentos $fkDoc = null)
    {
        $this->fkDoc = $fkDoc;

        return $this;
    }

    /**
     * Get fkDoc
     *
     * @return \BandejaBundle\Entity\Documentos
     */
    public function getFkDoc()
    {
        return $this->fkDoc;
    }

    /**
     * Set fkDepto
     *
     * @param \BandejaBundle\Entity\Departamentos $fkDepto
     *
     * @return Derivaciones
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

    /**
     * Set fkRemitente
     *
     * @param \BandejaBundle\Entity\Usuarios $fkRemitente
     *
     * @return Derivaciones
     */
    public function setFkRemitente(\BandejaBundle\Entity\Usuarios $fkRemitente = null)
    {
        $this->fkRemitente = $fkRemitente;

        return $this;
    }

    /**
     * Get fkRemitente
     *
     * @return \BandejaBundle\Entity\Usuarios
     */
    public function getFkRemitente()
    {
        return $this->fkRemitente;
    }

    /**
     * Set fkDestinatario
     *
     * @param \BandejaBundle\Entity\Usuarios $fkDestinatario
     *
     * @return Derivaciones
     */
    public function setFkDestinatario(\BandejaBundle\Entity\Usuarios $fkDestinatario = null)
    {
        $this->fkDestinatario = $fkDestinatario;

        return $this;
    }

    /**
     * Get fkDestinatario
     *
     * @return \BandejaBundle\Entity\Usuarios
     */
    public function getFkDestinatario()
    {
        return $this->fkDestinatario;
    }
}
