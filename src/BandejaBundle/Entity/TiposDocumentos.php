<?php

namespace BandejaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposDocumentos
 *
 * @ORM\Table(name="TIPOS_DOCUMENTOS")
 * @ORM\Entity
 */
class TiposDocumentos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_TIPOS_DOC", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTiposDoc;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="ABREV", type="string", length=10, nullable=true)
     */
    private $abrev;



    /**
     * Get idTiposDoc
     *
     * @return integer
     */
    public function getIdTiposDoc()
    {
        return $this->idTiposDoc;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return TiposDocumentos
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set abrev
     *
     * @param string $abrev
     *
     * @return TiposDocumentos
     */
    public function setAbrev($abrev)
    {
        $this->abrev = $abrev;

        return $this;
    }

    /**
     * Get abrev
     *
     * @return string
     */
    public function getAbrev()
    {
        return $this->abrev;
    }
}
