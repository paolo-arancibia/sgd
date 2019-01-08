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
     * @ORM\Column(name="rut", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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


}

