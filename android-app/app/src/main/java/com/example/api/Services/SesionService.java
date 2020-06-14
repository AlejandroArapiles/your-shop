package com.example.api.Services;

import com.example.api.Entity.Sesion;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.POST;
import retrofit2.http.Query;

/**
 * Clase que llama a la clase SesionController de la API
 */
public interface SesionService {

    /**
     * Inicia sesión en la aplicación
     * @param userLogin UserLogin Objeto que contiene un nombre de usuario y una contraseña
     * @return Sesion
     */
    @POST("login")
    public Call<Sesion> iniciarSesion(@Body UserLogin userLogin);

    /**
     * Cierra sesión en la aplicación
     * @param sesion Sesion sesion del usuario que desea cerrar sesión
     * @return Sesion
     */
    @POST("logout")
    public Call<ResponseBody> cerrarSesion(@Body Sesion sesion);
}
