<?php

namespace BandejaBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Adjuntos
 *
 * @ORM\Table(name="ADJUNTOS", indexes={@ORM\Index(name="FK_ID_DOC", columns={"FK_ID_DOC"}), @ORM\Index(name="FK_ID_USUARIO", columns={"FK_ID_USUARIO"})})
 * @ORM\Entity
 */
class Adjuntos
{
    const UPLOADED_FILE_DIRECTORY = '../src/BandejaBundle/Resources/uploads';
    const ABSOLUTE_FILE_DIRECTORY = '/var/www/html/sgd/src/BandejaBundle/Resources/uploads';

    /**
     * @var integer
     *
     * @ORM\Column(name="ID_ADJUNTO", type="integer")
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
     * @var string
     *
     * @ORM\Column(name="NOMBRE_ORIGINAL", type="string", length=255, nullable=false)
     */
    private $nombreOriginal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TIPO", type="boolean", nullable=true)
     */
    private $tipo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_C", type="integer", nullable=true)
     */
    private $fechaC;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_M", type="integer", nullable=true)
     */
    private $fechaM;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_E", type="integer", nullable=true)
     */
    private $fechaE;

    /**
     * @var \BandejaBundle\Entity\Departamentos
     *
     * @ORM\ManyToOne(targetEntity="BandejaBundle\Entity\Departamentos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_DOC", referencedColumnName="ID_DEPARTAMENTO")
     * })
     */
    private $fkDoc;

    /**
     * @var \BandejaBundle\Entity\Usuarios
     *
     * @ORM\ManyToOne(targetEntity="BandejaBundle\Entity\Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_ID_USUARIO", referencedColumnName="ID_USUARIO")
     * })
     */
    private $fkUsuario;


    private $file;

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
     * Set nombreOriginal
     *
     * @param string $nombreOriginal
     *
     * @return Adjuntos
     */
    public function setNombreOriginal($nombreOriginal)
    {
        $this->nombreOriginal = $nombreOriginal;

        return $this;
    }

    /**
     * Get nombreOriginal
     *
     * @return string
     */
    public function getNombreOriginal()
    {
        return $this->nombreOriginal;
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
     * @param \DateTime $fechaC
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
     * @return \DateTime
     */
    public function getFechaE()
    {
        return $this->fechaE;
    }

    /**
     * Set fkDoc
     *
     * @param \BandejaBundle\Entity\Documentos $fkDoc
     *
     * @return Adjuntos
     */
    public function setFkDoc(\BandejaBundle\Entity\Documentos $fkDoc = null)
    {
        $this->fkDoc = $fkDoc;

        return $this;
    }

    /**
     * Get fkDoc
     *
     * @return \BandejaBundle\Entity\Documentos
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

    public function getAbsolutePath()
    {
        return null === $this->url
                    ? null
                    : $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->url;
    }

    public function getWebPath()
    {
        return null === $this->url
                    ? null
                    : $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->url;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return self::ABSOLUTE_FILE_DIRECTORY;
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return self::UPLOADED_FILE_DIRECTORY;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     *
     * @return Adjuntos
     */
    public function setFile( UploadedFile $file )
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->url
        );

        // set the path property to the filename where you've saved the file
        //$this->url = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
}
