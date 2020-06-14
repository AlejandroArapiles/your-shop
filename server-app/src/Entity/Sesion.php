<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sesion
 * Clase que contiene las entidades de la tabla Sesion de la base de datos
 * @ORM\Table(name="Sesion", uniqueConstraints={@ORM\UniqueConstraint(name="sesion_usuario_UNIQUE", columns={"Sesion", "idUsuario_FK"})}, indexes={@ORM\Index(name="fk_Sesion_Usuario", columns={"idUsuario_FK"})})
 * @ORM\Entity(repositoryClass="App\Repository\SesionRepository")
 */
class Sesion
{
    /**
     * @var int
     * Identificador de la sesión
     * @ORM\Column(name="idSesion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"public"})
     */
    private $idsesion;

    /**
     * @var string
     * MD5 que interactúa con la aplicación a la hora de realizar operaciones
     * @ORM\Column(name="Sesion", type="string", length=60, nullable=false)
     * @Groups({"public"})
     */
    private $sesion;

    /**
     * @var \Usuario
     * Identificador del usuario al que pertenece la sesión
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUsuario_FK", referencedColumnName="idUsuario")
     * })
     * @Groups({"public"})
     */
    private $idusuarioFk;

    /**
     * @var string
     * Ultima conexión a la aplicación por parte del usuario
     * @Assert\DateTime
     * @ORM\Column(name="lastLogin", type="datetime", nullable=false)
     * @Groups({"public"})
     */
    private $lastLogin;

    /**
     * Devuelve el id de la sesion
     *
     * @return integer|null
     */
    public function getIdsesion(): ?int
    {
        return $this->idsesion;
    }

    /**
     * Devuelve el md5 de la sesion
     *
     * @return string|null
     */
    public function getSesion(): ?string
    {
        return $this->sesion;
    }

    /**
     * Actualiza el md5 de la sesion
     *
     * @param string $sesion
     * @return self
     */
    public function setSesion(string $sesion): self
    {
        $this->sesion = $sesion;

        return $this;
    }

    /**
     * Devuelve el usuario al que pertenece la sesion
     *
     * @return Usuario|null
     */
    public function getIdusuarioFk(): ?Usuario
    {
        return $this->idusuarioFk;
    }

    /**
     * Actualiza el usuario al que pertenece la sesion
     *
     * @param Usuario|null $idusuarioFk
     * @return self
     */
    public function setIdusuarioFk(?Usuario $idusuarioFk): self
    {
        $this->idusuarioFk = $idusuarioFk;

        return $this;
    }

    /**
     * Devuelve la ultima conexión del usuario a la aplicación
     *
     * @return \DateTime|null
     */
    public function getLastlogin(): ?\DateTime
    {
       return $this->lastLogin;
    }

    /**
     * Actualiza la ultima conexión del usuario a la aplicación
     *
     * @param \DateTime $lastLogin
     * @return self
     */
    public function setLastlogin(\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

}
