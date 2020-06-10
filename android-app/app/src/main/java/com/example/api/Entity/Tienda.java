package com.example.api.Entity;

public class Tienda {

    private int idtienda;
    private String nombretienda;
    private String cif;
    private String correocontacto;

    public int getIdtienda() {
        return idtienda;
    }

    public void setIdtienda(int idtienda) {
        this.idtienda = idtienda;
    }

    public String getNombretienda() {
        return nombretienda;
    }

    public void setNombretienda(String nombretienda) {
        this.nombretienda = nombretienda;
    }

    public String getCif() {
        return cif;
    }

    public void setCif(String cif) {
        this.cif = cif;
    }

    public String getCorreocontacto() {
        return correocontacto;
    }

    public void setCorreocontacto(String correocontacto) {
        this.correocontacto = correocontacto;
    }
}
