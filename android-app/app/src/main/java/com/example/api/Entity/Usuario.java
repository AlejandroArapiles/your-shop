package com.example.api.Entity;

/**
 * Clase que contiene las entidades de la tabla Usuario
 */
public class Usuario {

    /**
     * Atributos de un usuario
     */
    private int idusuario;
    private String nombreusuario;
    private String password;
    private String rol;
    private Tienda idtiendaFk;

    /**
     * Devuelve el id de un usuario
     * @return int
     */
    public int getIdusuario() {
        return idusuario;
    }

    /**
     * Actualiza el id de un usuario
     * @param idusuario int Determina el nuevo id del usuario
     */
    public void setIdusuario(int idusuario) {
        this.idusuario = idusuario;
    }

    /**
     * Devuelve el nombre del usuario
     * @return String
     */
    public String getNombreusuario() {
        return nombreusuario;
    }

    /**
     * Actualiza el nombre de un usuario
     * @param nombreusuario String Determina el nuevo nombre del usuario
     */
    public void setNombreusuario(String nombreusuario) {
        this.nombreusuario = nombreusuario;
    }

    /**
     * Devuelve la contraseña de un usuario
     * @return String
     */
    public String getPassword() {
        return password;
    }

    /**
     * Actualiza la contraseña de un usuario
     * @param password String Determina la nueva contraseña del usuario
     */
    public void setPassword(String password) {
        this.password = password;
    }

    /**
     * Devuelve el rol de un usuario
     * @return String
     */
    public String getRol() {
        return rol;
    }

    /**
     * Actualiza el rol de un usuario
     * @param rol String Determina el nuevo rol del usuario
     */
    public void setRol(String rol) {
        this.rol = rol;
    }

    /**
     * Devuelve la tienda a la que pertenece un usuario
     * @return Tienda
     */
    public Tienda getIdtiendaFk() {
        return idtiendaFk;
    }

    /**
     * Actualiza la tienda a la que pertenece un usuario
     * @param idtiendaFk Tienda Determina la nueva tienda del usuario
     */
    public void setIdtiendaFk(Tienda idtiendaFk) {
        this.idtiendaFk = idtiendaFk;
    }

    /**
     * Imprime los datos relevantes de un usuario
     * @return String
     */
    @Override
    public String toString() {
        return "-Nombre: " + nombreusuario + "\n- Rol: " + rol;
    }
}
