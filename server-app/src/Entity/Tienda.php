<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Tienda
 * Clase que contiene las entidades de la tabla Tienda de la base de datos
 * @ORM\Table(name="Tienda", uniqueConstraints={@ORM\UniqueConstraint(name="CIF_UNIQUE", columns={"CIF"})})
 * @ORM\Entity(repositoryClass="App\Repository\TiendaRepository")
 */
class Tienda
{
    /**
     * @var int
     * Identificador de la tienda
     * @ORM\Column(name="idTienda", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"public"})
     */
    private $idtienda;

    /**
     * @var string
     * Nombre de la tienda
     * @ORM\Column(name="NombreTienda", type="string", length=45, nullable=false)
     * @Groups({"public"})
     */
    private $nombretienda;

    /**
     * @var string
     * Número de identificación fiscal de la tienda
     * @ORM\Column(name="CIF", type="string", length=9, nullable=false)
     * @Groups({"public"})
     */
    private $cif;

    /**
     * @var string|null
     * Correo de contacto de la tienda
     * @ORM\Column(name="CorreoContacto", type="string", length=45, nullable=true)
     * @Groups({"public"})
     */
    private $correocontacto;

    /**
     * Devuelve el id de la tienda
     *
     * @return integer|null
     */
    public function getIdtienda(): ?int
    {
        return $this->idtienda;
    }

    /**
     * Devuelve el nombre de la tienda
     *
     * @return string|null
     */
    public function getNombretienda(): ?string
    {
        return $this->nombretienda;
    }

    /**
     * Actualiza el nombre de la tienda
     *
     * @param string $nombretienda
     * @return self
     */
    public function setNombretienda(string $nombretienda): self
    {
        $this->nombretienda = $nombretienda;

        return $this;
    }

    /**
     * Devuelve el CIF de la tienda
     *
     * @return string|null
     */
    public function getCif(): ?string
    {
        return $this->cif;
    }

    /**
     * Actualiza el CIF de la tienda
     *
     * @param string $cif
     * @return self
     */
    public function setCif(string $cif): self
    {
        $this->cif = $cif;

        return $this;
    }

    /**
     * Devuelve el correo de la tienda
     *
     * @return string|null
     */
    public function getCorreocontacto(): ?string
    {
        return $this->correocontacto;
    }

    /**
     * Actualiza el correo de la tienda
     *
     * @param string|null $correocontacto
     * @return self
     */
    public function setCorreocontacto(?string $correocontacto): self
    {
        $this->correocontacto = $correocontacto;

        return $this;
    }


}
