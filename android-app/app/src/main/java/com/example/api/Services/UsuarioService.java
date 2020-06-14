package com.example.api.Services;

import com.example.api.Entity.Usuario;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.DELETE;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Path;
import retrofit2.http.Query;

/**
 * Clase que llama a la clase UsuarioController  de la API
 */
public interface UsuarioService {

    /**
     * Registra una nueva tienda y un usuario asociado a esta
     * @param usuario Usuario Determina los datos de un objeto Usuario
     * @return Usuario
     */
    @POST("register")
    Call<Usuario> registrarUsuarioTienda(@Body Usuario usuario);

    /**
     * Lista los usuarios de una tienda
     * @param idTienda int Determina el id de la tienda a la que pertenecen los usuarios
     * @param token String Determina si el usuario está logado
     * @return List<Usuario>
     */
    @GET("list/usuario/{idTienda}")
    Call<List<Usuario>> listarUsuarios(@Path("idTienda") int idTienda, @Query("token") String token);

    /**
     * Devuelve los datos de un producto
     * @param idUsuario int Determina de que usuario saca los datos
     * @param token String Determina si el usuario está logado
     * @return Usuario
     */
    @GET("view/usuario/{idUsuario}")
    Call<Usuario> verUsuario(@Path("idUsuario") int idUsuario, @Query("token") String token);

    /**
     * Crea un usuario
     * @param usuario Determina el usuario que se crea en la tabla Usuario
     * @param token String Determina si el usuario está logado
     * @return Usuario
     */
    @POST("insert/usuario")
    Call<Usuario> crearUsuario(@Body Usuario usuario, @Query("token") String token);

    /**
     * Elimina un usuario
     * @param idUsuario int Determina el id del usuario a eliminar
     * @param token String Determina si el usuario está logado
     * @return Usuario
     */
    @DELETE("delete/usuario/{idUsuario}")
    Call<Usuario> eliminarUsuario(@Path("idUsuario") int idUsuario, @Query("token") String token);

    /**
     * Modifica un usuario
     * @param idUsuario int Determina el id del usuario a modificar
     * @param usuario Usuario Determina los datos del usuario a modificar
     * @param token String Determina si el usuario está logado
     * @return Usuario
     */
    @POST("modify/usuario/{idUsuario}")
    Call<Usuario> modificarUsuario(@Path("idUsuario") int idUsuario, @Body Usuario usuario,  @Query("token") String token );

    /**
     * Modifica la contraseña de un usuario
     * @param idUsuario int Determina el id del usuario a modificar la contraseña
     * @param usuario Usuario Determina la nueva contraseña del usuario
     * @param token String Determina si el usuario está logado
     * @return Usuario
     */
    @POST("modify/perfil/{idUsuario}")
    Call<Usuario> modificarPerfil(@Path("idUsuario") int idUsuario, @Body Usuario usuario,  @Query("token") String token );

}
