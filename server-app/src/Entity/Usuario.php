<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Usuario
 * Clase que contiene las entidades de la tabla Usuario de la base de datos
 * @ORM\Table(name="Usuario", uniqueConstraints={@ORM\UniqueConstraint(name="usuario_tienda_UNIQUE", columns={"NombreUsuario", "idTienda_FK"})}, indexes={@ORM\Index(name="FK_idTienda", columns={"idTienda_FK"})})
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario
{
    /**
     * @var int
     * Identificador del usuario
     * @ORM\Column(name="idUsuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"public"})
     */
    private $idusuario;

    /**
     * @var string
     * Nombre del usuario
     * @ORM\Column(name="NombreUsuario", type="string", length=100, nullable=false)
     * @Groups({"public"})
     */
    private $nombreusuario;

    /**
     * @var string
     * Rol del usuario
     * @ORM\Column(name="Rol", type="string", length=15, nullable=false)
     * @Groups({"public"})
     */
    private $rol;

    /**
     * @var string
     * Contraseña del usuario
     * @ORM\Column(name="Password", type="string", length=45, nullable=false)
     */
    private $password;

    /**
     * @var \Tienda
     * Tienda a la que pertenece el usuario
     * @ORM\ManyToOne(targetEntity="Tienda")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTienda_FK", referencedColumnName="idTienda")
     * })
     * @Groups({"public"})
     */
    private $idtiendaFk;

    /**
     * Devuelve el id del usuario
     *
     * @return integer|null
     */
    public function getIdusuario(): ?int
    {
        return $this->idusuario;
    }

    /**
     * Devuelve el nombre del usuario
     *
     * @return string|null
     */
    public function getNombreusuario(): ?string
    {
        return $this->nombreusuario;
    }

    /**
     * Actualiza el nombre del usuario
     *
     * @param string $nombreusuario
     * @return self
     */
    public function setNombreusuario(string $nombreusuario): self
    {
        $this->nombreusuario = $nombreusuario;

        return $this;
    }

    /**
     * Devuelve el rol del usuario
     *
     * @return string|null
     */
    public function getRol(): ?string
    {
        return $this->rol;
    }

    /**
     * Actualiza el rol del usuario
     *
     * @param string $rol
     * @return self
     */
    public function setRol(string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Devuelve el md5 de la contraseña
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Actualiza el md5 de la contraseña
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Devuelve la tienda a la que pertenece el usuario
     *
     * @return Tienda|null
     */
    public function getIdtiendaFk(): ?Tienda
    {
        return $this->idtiendaFk;
    }

    /**
     * Actualiza la tienda a la que pertenece el usuario
     *
     * @param Tienda|null $idtiendaFk
     * @return self
     */
    public function setIdtiendaFk(?Tienda $idtiendaFk): self
    {
        $this->idtiendaFk = $idtiendaFk;

        return $this;
    }


}
