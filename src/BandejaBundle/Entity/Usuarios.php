<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuarios
 *
 * @ORM\Table(name="USUARIOS", indexes={@ORM\Index(name="FK_ID_PERSONA", columns={"FK_ID_PERSONA"})})
 * @ORM\Entity
 */
class Usuarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_USUARIO", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUsuario;

    /**
     * @var string
     *
     * @ORM\Column(name="NOMBRE", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="CONTRASEÑA", type="string", length=255, nullable=true)
     */
    private $contraseÑa;

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
     * @ORM\Column(name="FECHA_E", type="datetime", nullable=false)
     */
    private $fechaE;

    /**
     * @var \Personas
     *
     * @ORM\ManyToOne(targetEntity="Personas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_PERSONA", referencedColumnName="rut")
     * })
     */
    private $fkPersona;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Departamentos", mappedBy="fkUsuario")
     */
    private $fkDepto;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fkDepto = new \Doctrine\Common\Collections\ArrayCollection();
    }

}

