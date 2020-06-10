package com.example.api.Entity;

public class Sesion {

    private int idsesion;
    private String sesion;
    private Usuario idusuarioFk;
    private String lastLogin;

    public int getIdsesion() {
        return idsesion;
    }

    public void setIdsesion(int idsesion) {
        this.idsesion = idsesion;
    }

    public String getSesion() {
        return sesion;
    }

    public void setSesion(String sesion) {
        this.sesion = sesion;
    }

    public Usuario getIdusuarioFk() {
        return idusuarioFk;
    }

    public void setIdusuarioFk(Usuario idusuarioFk) {
        this.idusuarioFk = idusuarioFk;
    }

    public String getLastLogin() {
        return lastLogin;
    }

    public void setLastLogin(String lastLogin) {
        this.lastLogin = lastLogin;
    }
}
