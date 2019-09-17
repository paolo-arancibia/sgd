<?php

namespace BandejaBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuarios
 *
 * @ORM\Table(name="USUARIOS", indexes={@ORM\Index(name="FK_ID_PERSONA", columns={"FK_ID_PERSONA"})})
 * @ORM\Entity
 */
class Usuarios implements UserInterface, \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID_USUARIO", type="integer")
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
     * @ORM\Column(name="CONTRASENA", type="string", length=255, nullable=true)
     * para crear contraseñas utilice el comando:
     *  $ php -r ' echo password_hash("<su_contraseña>", PASSWORD_DEFAULT)."\n";'
     */
    private $contrasena;

    const ENCRYPT_TYPE = PASSWORD_DEFAULT;

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
     * @ORM\Column(name="FECHA_E", type="datetime", nullable=true)
     */
    private $fechaE;

    /**
     * @var \BandejaBundle\Entity\Personas
     *
     * @ORM\ManyToOne(targetEntity="BandejaBundle\Entity\Personas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_PERSONA", referencedColumnName="rut")
     * })
     */
    private $fkPersona;

    private $isActive;

    /**
     * @var \Collection
     *
     * @ORM\OneToMay(targetEntity="BandejaBundle\Entity\DepUsu", mappedBy="fkUsuario", fetch="LAZY")
     */
    private $depUsus;

    public function __construct()
    {
        $this->isActive = true;
        $this->depUsus = new Collection();
    }

    /**
     * Get idUsuario
     *
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuarios
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
     * Set contrasena
     *
     * @param string $contrasena
     *
     * @return Usuarios
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set fechaC
     *
     * @param \DateTime $fechaC
     *
     * @return Usuarios
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
     * @return Usuarios
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
     * @return Usuarios
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
     * Set fkPersona
     *
     * @param \BandejaBundle\Entity\Personas $fkPersona
     *
     * @return Usuarios
     */
    public function setFkPersona(\BandejaBundle\Entity\Personas $fkPersona = null)
    {
        $this->fkPersona = $fkPersona;

        return $this;
    }

    /**
     * Get fkPersona
     *
     * @return \BandejaBundle\Entity\Personas
     */
    public function getFkPersona()
    {
        return $this->fkPersona;
    }

    /**
     * Get getUsername
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->nombre;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * Get getPassword
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->contrasena;
    }

    /**
     * Get depUsus
     *
     * @return \Collection
     */
    public function getDepUsus()
    {
        return $this->depUsus;
    }

    /**
     * Add a DepUsu to depUsus
     *
     * @param \DepUsu $depUsu
     *
     * @return Usuarios
     */
    public function addDepUsus($depUsu)
    {
        if( $this->depUsus->contains($depUsu) )
        {
            return;
        }

        $this->depUsus->add($depUsu);

        return $this;
    }

    /**
     * Remove a DepUsu to depUsus
     *
     * @param \DepUsu $depUsu
     *
     * @return Usuarios
     */
    public function removeDepUsus($depUsu)
    {
        if( ! $this->depUsus->contains($depUsu) )
        {
            return;
        }

        $this->depUsus->removeElement($depUsu);

        return $this;
    }

    /**
     * Get getRoles
     *
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_ACCESS');
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->idUsuario,
            $this->nombre,
            $this->contrasena,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->idUsuario,
            $this->nombre,
            $this->contrasena,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
}
