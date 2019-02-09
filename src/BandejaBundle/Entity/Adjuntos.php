<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adjuntos
 *
 * @ORM\Table(name="ADJUNTOS", indexes={@ORM\Index(name="FK_ID_DOC", columns={"FK_ID_DOC"}), @ORM\Index(name="FK_ID_USUARIO", columns={"FK_ID_USUARIO"})})
 * @ORM\Entity
 */
class Adjuntos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ADJUNTO", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAdjunto;

    /**
     * @var string
     *
     * @ORM\Column(name="URL", type="string", length=512, nullable=false)
     */
    private $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TIPO", type="boolean", nullable=true)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="FECHA_C", type="integer", nullable=true)
     */
    private $fechaC;

    /**
     * @var integer
     *
     * @ORM\Column(name="FECHA_M", type="integer", nullable=true)
     */
    private $fechaM;

    /**
     * @var integer
     *
     * @ORM\Column(name="FECHA_E", type="integer", nullable=true)
     */
    private $fechaE;

    /**
     * @var \Departamentos
     *
     * @ORM\ManyToOne(targetEntity="Departamentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_DOC", referencedColumnName="ID_DEPARTAMENTO")
     * })
     */
    private $fkDoc;

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
     * Get idAdjunto
     *
     * @return integer
     */
    public function getIdAdjunto()
    {
        return $this->idAdjunto;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Adjuntos
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set tipo
     *
     * @param boolean $tipo
     *
     * @return Adjuntos
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
     * Set fechaC
     *
     * @param integer $fechaC
     *
     * @return Adjuntos
     */
    public function setFechaC($fechaC)
    {
        $this->fechaC = $fechaC;

        return $this;
    }

    /**
     * Get fechaC
     *
     * @return integer
     */
    public function getFechaC()
    {
        return $this->fechaC;
    }

    /**
     * Set fechaM
     *
     * @param integer $fechaM
     *
     * @return Adjuntos
     */
    public function setFechaM($fechaM)
    {
        $this->fechaM = $fechaM;

        return $this;
    }

    /**
     * Get fechaM
     *
     * @return integer
     */
    public function getFechaM()
    {
        return $this->fechaM;
    }

    /**
     * Set fechaE
     *
     * @param integer $fechaE
     *
     * @return Adjuntos
     */
    public function setFechaE($fechaE)
    {
        $this->fechaE = $fechaE;

        return $this;
    }

    /**
     * Get fechaE
     *
     * @return integer
     */
    public function getFechaE()
    {
        return $this->fechaE;
    }

    /**
     * Set fkDoc
     *
     * @param \BandejaBundle\Entity\Departamentos $fkDoc
     *
     * @return Adjuntos
     */
    public function setFkDoc(\BandejaBundle\Entity\Departamentos $fkDoc = null)
    {
        $this->fkDoc = $fkDoc;

        return $this;
    }

    /**
     * Get fkDoc
     *
     * @return \BandejaBundle\Entity\Departamentos
     */
    public function getFkDoc()
    {
        return $this->fkDoc;
    }

    /**
     * Set fkUsuario
     *
     * @param \BandejaBundle\Entity\Usuarios $fkUsuario
     *
     * @return Adjuntos
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
}
