package com.example.api.Entity;

/**
 * Clase que contiene las entidades de la tabla Sesion
 */
public class Sesion {

    /**
     * Atributos de una sesion
     */
    private int idsesion;
    private String sesion;
    private Usuario idusuarioFk;
    private String lastLogin;

    /**
     * Devuelve el id de una sesión
     * @return int
     */
    public int getIdsesion() {
        return idsesion;
    }

    /**
     * Actualiza el id de una sesión
     * @param idsesion int Determina el nuevo id de la sesión
     */
    public void setIdsesion(int idsesion) {
        this.idsesion = idsesion;
    }

    /**
     * Devuelve el MD5 de una sesión
     * @return String
     */
    public String getSesion() {
        return sesion;
    }

    /**
     * Actualiza el MD5 de una sesión
     * @param sesion String Determina el nuevo MD5 de una sesión
     */
    public void setSesion(String sesion) {
        this.sesion = sesion;
    }

    /**
     * Devuelve el usuario a la que pertenece la sesión
     * @return Usuario
     */
    public Usuario getIdusuarioFk() {
        return idusuarioFk;
    }

    /**
     * Actualiza el usuario al que pertenece la sesión
     * @param idusuarioFk  Usuario Determina el nuevo usuario de la sesión
     */
    public void setIdusuarioFk(Usuario idusuarioFk) {
        this.idusuarioFk = idusuarioFk;
    }

    /**
     * Devuelve el lastlogin a la aplicación de un usuario
     * @return String
     */
    public String getLastLogin() {
        return lastLogin;
    }

    /**
     * Actualiza el lastlogin a la aplicación de un usuario
     * @param lastLogin String Determina el nuevo lastlogin de un usuario
     */
    public void setLastLogin(String lastLogin) {
        this.lastLogin = lastLogin;
    }
}
