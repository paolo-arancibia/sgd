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


}

