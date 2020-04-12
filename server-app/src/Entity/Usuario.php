<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\Table(name="Usuario", uniqueConstraints={@ORM\UniqueConstraint(name="usuario_tienda_UNIQUE", columns={"NombreUsuario", "idTienda_FK"})}, indexes={@ORM\Index(name="FK_idTienda", columns={"idTienda_FK"})})
 * @ORM\Entity(repositoryClass="App\Repository\UsuarioRepository")
 */
class Usuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="idUsuario", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="NombreUsuario", type="string", length=100, nullable=false)
     */
    private $nombreusuario;

    /**
     * @var string
     *
     * @ORM\Column(name="Rol", type="string", length=15, nullable=false)
     */
    private $rol;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=45, nullable=false)
     */
    private $password;

    /**
     * @var \Tienda
     *
     * @ORM\ManyToOne(targetEntity="Tienda")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTienda_FK", referencedColumnName="idTienda")
     * })
     */
    private $idtiendaFk;

    public function getIdusuario(): ?int
    {
        return $this->idusuario;
    }

    public function getNombreusuario(): ?string
    {
        return $this->nombreusuario;
    }

    public function setNombreusuario(string $nombreusuario): self
    {
        $this->nombreusuario = $nombreusuario;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIdtiendaFk(): ?Tienda
    {
        return $this->idtiendaFk;
    }

    public function setIdtiendaFk(?Tienda $idtiendaFk): self
    {
        $this->idtiendaFk = $idtiendaFk;

        return $this;
    }


}
