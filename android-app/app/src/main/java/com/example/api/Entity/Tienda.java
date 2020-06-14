package com.example.api.Entity;

/**
 * Clase que contiene las entidades de la tabla Tienda
 */
public class Tienda {

    /**
     * Atributos de una tienda
     */
    private int idtienda;
    private String nombretienda;
    private String cif;
    private String correocontacto;

    /**
     * Devuelve el id de una tienda
     * @return int
     */
    public int getIdtienda() {
        return idtienda;
    }

    /**
     * Actualiza el id de una tienda
     * @param idtienda int Determina el nuevo de la tienda
     */
    public void setIdtienda(int idtienda) {
        this.idtienda = idtienda;
    }

    /**
     * Devuelve el nombre de una tienda
     * @return String
     */
    public String getNombretienda() {
        return nombretienda;
    }

    /**
     * Actualiza el nombre de una tienda
     * @param nombretienda String Determina el nuevo nombre de la tienda
     */
    public void setNombretienda(String nombretienda) {
        this.nombretienda = nombretienda;
    }

    /**
     * Devuelve el CIF de una tienda
     * @return String
     */
    public String getCif() {
        return cif;
    }

    /**
     * Actualiza el cif de una tienda
     * @param cif String Determina el nuevo cif de la tienda
     */
    public void setCif(String cif) {
        this.cif = cif;
    }

    /**
     * Devuelve el correo de contacto de una tienda
     * @return String
     */
    public String getCorreocontacto() {
        return correocontacto;
    }

    /**
     * Actualiza el correo de contacto de una tienda
     * @param correocontacto String Determina el nuevo correo de contacto de la tienda
     */
    public void setCorreocontacto(String correocontacto) {
        this.correocontacto = correocontacto;
    }
}
