package com.example.api.Entity;

public class Usuario {

    private int idusuario;
    private String nombreusuario;
    private String password;
    private String rol;
    private Tienda idtiendaFk;

    public int getIdusuario() {
        return idusuario;
    }

    public void setIdusuario(int idusuario) {
        this.idusuario = idusuario;
    }

    public String getNombreusuario() {
        return nombreusuario;
    }

    public void setNombreusuario(String nombreusuario) {
        this.nombreusuario = nombreusuario;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getRol() {
        return rol;
    }

    public void setRol(String rol) {
        this.rol = rol;
    }

    public Tienda getIdtiendaFk() {
        return idtiendaFk;
    }

    public void setIdtiendaFk(Tienda idtiendaFk) {
        this.idtiendaFk = idtiendaFk;
    }

    @Override
    public String toString() {
        return nombreusuario + " - " + rol;
    }
}
