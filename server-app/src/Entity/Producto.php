<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Producto
 * Clase que contiene las entidades de la tabla Producto de la base de datos
 * @ORM\Table(name="Producto", uniqueConstraints={@ORM\UniqueConstraint(name="nombre_tienda_UNIQUE", columns={"NombreProducto", "idTienda_FK"})}, indexes={@ORM\Index(name="fk_Producto_Tienda1", columns={"idTienda_FK"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProductoRepository")
 */
class Producto
{
    /**
     * @var int
     * Identificador del producto
     * @ORM\Column(name="idProducto", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"public"})
     */
    private $idproducto;

    /**
     * @var string
     * Nombre del producto
     * @ORM\Column(name="NombreProducto", type="string", length=255, nullable=false)
     * @Groups({"public"})
     */
    private $nombreproducto;

    /**
     * @var string|null
     * Descripción del producto
     * @ORM\Column(name="Descripcion", type="string", length=500, nullable=true)
     * @Groups({"public"})
     */
    private $descripcion;

    /**
     * @var int|null
     * Cantidad de unidades disponibles del producto
     * @ORM\Column(name="Cantidad", type="integer", nullable=true)
     * @Groups({"public"})
     */
    private $cantidad = '0';

    /**
     * @var string
     * Precio por unidad del producto
     * @ORM\Column(name="Precio", type="decimal", precision=20, scale=2, nullable=false)
     * @Groups({"public"})
     */
    private $precio;

    /**
     * @var int|null
     * Indica si el producto está en venta actualmente
     * @ORM\Column(name="Activo", type="integer", nullable=true, options={"default"="1"})
     * @Groups({"public"})
     */
    private $activo = '1';

    /**
     * @var \Tienda
     * Tienda a la que pertenece el producto
     * @ORM\ManyToOne(targetEntity="Tienda")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTienda_FK", referencedColumnName="idTienda")
     * })
     * @Groups({"public"})
     */
    private $idtiendaFk;

    /**
     * Devuelve el id del producto
     *
     * @return integer|null
     */
    public function getIdproducto(): ?int
    {
        return $this->idproducto;
    }

    /**
     * Devuelve el nombre del producto
     *
     * @return string|null
     */
    public function getNombreproducto(): ?string
    {
        return $this->nombreproducto;
    }

    /**
     * Actualiza el nombre del producto 
     *
     * @param string $nombreproducto
     * @return self
     */
    public function setNombreproducto(string $nombreproducto): self
    {
        $this->nombreproducto = $nombreproducto;

        return $this;
    }

    /**
     * Devuelve la descripción del producto
     *
     * @return string|null
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * Actualiza la descripcion del producto
     *
     * @param string|null $descripcion
     * @return self
     */
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Devuelve la cantidad de stock disponible del producto
     *
     * @return integer|null
     */
    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    /**
     * Actualiza la cantidad de stock disponible del producto
     *
     * @param integer|null $cantidad
     * @return self
     */
    public function setCantidad(?int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Devuelve el precio por unidad del producto
     *
     * @return string|null
     */
    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    /**
     * Actualiza el precio por unidad del producto
     *
     * @param string $precio
     * @return self
     */
    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Actualiza si el producto está activo o no
     *
     * @param integer|null $activo
     * @return self
     */
    public function setActivo(?int $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Devuelve true si el producto es activo sino false
     *
     * @return boolean
     */
    public function isActivo()
    {
        return (boolean)$this->activo;
    }

    /**
     * Devuelve la tienda a la que pertenece el producto
     *
     * @return Tienda|null
     */
    public function getIdtiendaFk(): ?Tienda
    {
        return $this->idtiendaFk;
    }

    /**
     * Actualiza la tienda a la que pertenece el producto
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
