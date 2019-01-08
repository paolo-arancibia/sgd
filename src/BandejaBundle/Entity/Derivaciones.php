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


}

