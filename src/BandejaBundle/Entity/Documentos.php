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
     * @ORM\Column(name="FEHCA_C", type="datetime", nullable=true)
     */
    private $fehcaC;

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


}

