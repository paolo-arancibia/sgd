<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documentos
 *
 * @ORM\Table(name="DOCUMENTOS", indexes={@ORM\Index(name="FK_ID_USUARIO", columns={"FK_ID_USUARIO"}), @ORM\Index(name="FK_ID_TIPO_DOC", columns={"FK_ID_TIPO_DOC"})})
 * @ORM\Entity
 */
class Documentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_DOC", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDoc;

    /**
     * @var integer
     *
     * @ORM\Column(name="NRO_EXPEDIENTE", type="integer", nullable=true)
     */
    private $nroExpediente;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ESTADO", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_DOC", type="date", nullable=true)
     */
    private $fechaDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="ANT", type="text", length=65535, nullable=true)
     */
    private $ant;

    /**
     * @var string
     *
     * @ORM\Column(name="MAT", type="text", length=65535, nullable=true)
     */
    private $mat;

    /**
     * @var string
     *
     * @ORM\Column(name="EXT", type="text", length=65535, nullable=true)
     */
    private $ext;

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
     * @var \Usuarios
     *
     * @ORM\ManyToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_USUARIO", referencedColumnName="ID_USUARIO")
     * })
     */
    private $fkUsuario;

    /**
     * @var \TiposDocumentos
     *
     * @ORM\ManyToOne(targetEntity="TiposDocumentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_TIPO_DOC", referencedColumnName="ID_TIPOS_DOC")
     * })
     */
    private $fkTipoDoc;



    /**
     * Get idDoc
     *
     * @return integer
     */
    public function getIdDoc()
    {
        return $this->idDoc;
    }

    /**
     * Set nroExpediente
     *
     * @param integer $nroExpediente
     *
     * @return Documentos
     */
    public function setNroExpediente($nroExpediente)
    {
        $this->nroExpediente = $nroExpediente;

        return $this;
    }

    /**
     * Get nroExpediente
     *
     * @return integer
     */
    public function getNroExpediente()
    {
        return $this->nroExpediente;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return Documentos
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set fechaDoc
     *
     * @param \DateTime $fechaDoc
     *
     * @return Documentos
     */
    public function setFechaDoc($fechaDoc)
    {
        $this->fechaDoc = $fechaDoc;

        return $this;
    }

    /**
     * Get fechaDoc
     *
     * @return \DateTime
     */
    public function getFechaDoc()
    {
        return $this->fechaDoc;
    }

    /**
     * Set ant
     *
     * @param string $ant
     *
     * @return Documentos
     */
    public function setAnt($ant)
    {
        $this->ant = $ant;

        return $this;
    }

    /**
     * Get ant
     *
     * @return string
     */
    public function getAnt()
    {
        return $this->ant;
    }

    /**
     * Set mat
     *
     * @param string $mat
     *
     * @return Documentos
     */
    public function setMat($mat)
    {
        $this->mat = $mat;

        return $this;
    }

    /**
     * Get mat
     *
     * @return string
     */
    public function getMat()
    {
        return $this->mat;
    }

    /**
     * Set ext
     *
     * @param string $ext
     *
     * @return Documentos
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return Documentos
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
     * @return Documentos
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
     * @return Documentos
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
     * @return Documentos
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
     * Set fkTipoDoc
     *
     * @param \BandejaBundle\Entity\TiposDocumentos $fkTipoDoc
     *
     * @return Documentos
     */
    public function setFkTipoDoc(\BandejaBundle\Entity\TiposDocumentos $fkTipoDoc = null)
    {
        $this->fkTipoDoc = $fkTipoDoc;

        return $this;
    }

    /**
     * Get fkTipoDoc
     *
     * @return \BandejaBundle\Entity\TiposDocumentos
     */
    public function getFkTipoDoc()
    {
        return $this->fkTipoDoc;
    }
}
