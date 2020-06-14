package com.example.api.Services;

/**
 * Clase que contiene el nombre y contraseña de un Usuario
 */
public class UserLogin {

    /**
     * Atributos de UserLogin
     */
    private String usuario;
    private String password;

    /**
     * Devuelve el nombre de un usuario
     * @return String
     */
    public String getUsuario() {
        return usuario;
    }

    /**
     * Actualiza el nombre de un usuario
     * @param usuario String Determina el nuevo nombre del usuario
     */
    public void setUsuario(String usuario) {
        this.usuario = usuario;
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
}
