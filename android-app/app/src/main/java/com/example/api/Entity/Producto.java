package com.example.api.Entity;

/**
 * Clase que contiene las entidades de la tabla Producto
 */
public class Producto {

    /**
     * Atributos de un producto
     */
    private int idproducto;
    private String nombreproducto;
    private String descripcion;
    private int cantidad;
    private double precio;
    private boolean activo;
    private Tienda idtiendaFk;

    /**
     * Devuelve el id de un producto
     * @return int
     */
    public int getIdproducto() {
        return idproducto;
    }

    /**
     * Actualiza el id de un producto
     * @param idproducto int Determina el nuevo id del producto
     */
    public void setIdproducto(int idproducto) {
        this.idproducto = idproducto;
    }

    /**
     * Devuelve el nombre de un producto
     * @return String
     */
    public String getNombreproducto() {
        return nombreproducto;
    }

    /**
     * Actualiza el nombre de un producto
     * @param nombreproducto String Determina el nuevo nombre del producto
     */
    public void setNombreproducto(String nombreproducto) {
        this.nombreproducto = nombreproducto;
    }

    /**
     * Devuelve la descripción de un producto
     * @return String
     */
    public String getDescripcion() {
        return descripcion;
    }

    /**
     * Actualiza la descripción de un producto
     * @param descripcion String Determina la nueva descripción del producto
     */
    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    /**
     * Devuelve la cantidad de stock de un producto
     * @return int
     */
    public int getCantidad() {
        return cantidad;
    }

    /**
     * Actualiza la cantidad de stock de un producto
     * @param cantidad int Determina la nueva cantidad de un producto
     */
    public void setCantidad(int cantidad) {
        this.cantidad = cantidad;
    }

    /**
     * Devuelve el precio de un producto
     * @return double
     */
    public double getPrecio() {
        return precio;
    }

    /**
     * Actualiza el precio de un producto
     * @param precio int Determina el nuevo precio del producto
     */
    public void setPrecio(double precio) {
        this.precio = precio;
    }

    /**
     * Devuelve si un producto es activo o no
     * @return boolean
     */
    public boolean isActivo() {
        return activo;
    }

    /**
     * Actualiza si el producto es activo o no
     * @param activo boolean Determina si el producto está activo o no
     */
    public void setActivo(boolean activo) {
        this.activo = activo;
    }

    /**
     * Devuelve la tienda a la que pertenece el producto
     * @return Tienda
     */
    public Tienda getIdtiendaFk() {
        return idtiendaFk;
    }

    /**
     * Actualiza la tienda a la que pertenece el producto
     * @param idtiendaFk Tienda Determina la nueva tienda del producto
     */
    public void setIdtiendaFk(Tienda idtiendaFk) {
        this.idtiendaFk = idtiendaFk;
    }

    /**
     * Imprime los datos relevantes de un producto
     * @return String
     */
    @Override
    public String toString() {
        return "-Nombre: " + nombreproducto + "\n- Cantidad: " + cantidad + "\n- Precio: " + precio;
    }
}
