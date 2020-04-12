<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tienda
 *
 * @ORM\Table(name="Tienda", uniqueConstraints={@ORM\UniqueConstraint(name="CIF_UNIQUE", columns={"CIF"})})
 * @ORM\Entity(repositoryClass="App\Repository\TiendaRepository")
 */
class Tienda
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTienda", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idtienda;

    /**
     * @var string
     *
     * @ORM\Column(name="NombreTienda", type="string", length=45, nullable=false)
     */
    private $nombretienda;

    /**
     * @var string
     *
     * @ORM\Column(name="CIF", type="string", length=9, nullable=false)
     */
    private $cif;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CorreoContacto", type="string", length=45, nullable=true)
     */
    private $correocontacto;

    public function getIdtienda(): ?int
    {
        return $this->idtienda;
    }

    public function getNombretienda(): ?string
    {
        return $this->nombretienda;
    }

    public function setNombretienda(string $nombretienda): self
    {
        $this->nombretienda = $nombretienda;

        return $this;
    }

    public function getCif(): ?string
    {
        return $this->cif;
    }

    public function setCif(string $cif): self
    {
        $this->cif = $cif;

        return $this;
    }

    public function getCorreocontacto(): ?string
    {
        return $this->correocontacto;
    }

    public function setCorreocontacto(?string $correocontacto): self
    {
        $this->correocontacto = $correocontacto;

        return $this;
    }


}
