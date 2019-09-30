<?php

namespace AccessBundle\Entity;

/**
 * App
 */
class App
{
    /**
     * @var integer
     */
    private $idApp;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $img;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $fechaC;

    /**
     * @var \DateTime
     */
    private $fechaM;

    /**
     * @var \DateTime
     */
    private $fechaE;

    /**
     * @var \Collection
     *
     * @ORM\OneToMay(targetEntity="AccessBundle\Entity\Permisos", mappedBy="fkApp", fetch="LAZY")
     */
    private $fkPermisos;

    /**
     * Get idApp
     *
     * @return integer
     */
    public function getIdApp()
    {
        return $this->idApp;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return App
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return App
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return App
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
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return App
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
     * @return App
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
     * @return App
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
     * Get permisos
     *
     * @return \Collection
     */
    public function getFkPermisos()
    {
        return $this->fkPermisos;
    }

    /**
     * Add a permiso to fkPermisos
     *
     * @param \Permisos $permiso
     *
     * @return App
     */
    public function addFkPermisos($permiso)
    {
        if( $this->fkPermisos->contains($permiso) )
        {
            return;
        }

        $this->fkPermisos->add($permiso);

        return $this;
    }

    /**
     * Remove a permiso from fkPermisos
     *
     * @param \Permisos $permiso
     *
     * @return App
     */
    public function removeFkPermisos($permisos)
    {
        if( ! $this->fkPermisos->contains($permiso) )
        {
            return;
        }

        $this->fkPermisos->removeElement($permiso);

        return $this;
    }


}
