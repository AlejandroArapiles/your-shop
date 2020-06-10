package com.example.api.Entity;

public class Producto {

    private int idproducto;
    private String nombreproducto;
    private String descripcion;
    private int cantidad;
    private double precio;
    private boolean activo;
    private Tienda idtiendaFk;

    public int getIdproducto() {
        return idproducto;
    }

    public void setIdproducto(int idproducto) {
        this.idproducto = idproducto;
    }

    public String getNombreproducto() {
        return nombreproducto;
    }

    public void setNombreproducto(String nombreproducto) {
        this.nombreproducto = nombreproducto;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public int getCantidad() {
        return cantidad;
    }

    public void setCantidad(int cantidad) {
        this.cantidad = cantidad;
    }

    public double getPrecio() {
        return precio;
    }

    public void setPrecio(double precio) {
        this.precio = precio;
    }

    public boolean isActivo() {
        return activo;
    }

    public void setActivo(boolean activo) {
        this.activo = activo;
    }

    public Tienda getIdtiendaFk() {
        return idtiendaFk;
    }

    public void setIdtiendaFk(Tienda idtiendaFk) {
        this.idtiendaFk = idtiendaFk;
    }

    @Override
    public String toString() {
        return nombreproducto + " - " + cantidad + " - " + precio;
    }
}
