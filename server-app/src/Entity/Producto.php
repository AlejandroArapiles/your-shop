<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Producto
 *
 * @ORM\Table(name="Producto", uniqueConstraints={@ORM\UniqueConstraint(name="nombre_tienda_UNIQUE", columns={"NombreProducto", "idTienda_FK"})}, indexes={@ORM\Index(name="fk_Producto_Tienda1", columns={"idTienda_FK"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductoRepository")
 */
class Producto
{
    /**
     * @var int
     *
     * @ORM\Column(name="idProducto", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idproducto;

    /**
     * @var string
     *
     * @ORM\Column(name="NombreProducto", type="string", length=100, nullable=false)
     */
    private $nombreproducto;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Cantidad", type="integer", nullable=true)
     */
    private $cantidad = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="Precio", type="decimal", precision=20, scale=2, nullable=false)
     */
    private $precio;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Activo", type="integer", nullable=true, options={"default"="1"})
     */
    private $activo = '1';

    /**
     * @var \Tienda
     *
     * @ORM\ManyToOne(targetEntity="Tienda")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTienda_FK", referencedColumnName="idTienda")
     * })
     */
    private $idtiendaFk;

    public function getIdproducto(): ?int
    {
        return $this->idproducto;
    }

    public function getNombreproducto(): ?string
    {
        return $this->nombreproducto;
    }

    public function setNombreproducto(string $nombreproducto): self
    {
        $this->nombreproducto = $nombreproducto;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getActivo(): ?int
    {
        return $this->activo;
    }

    public function setActivo(?int $activo): self
    {
        $this->activo = $activo;

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
