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

public interface UsuarioService {

    @POST("register")
    Call<Usuario> registrarUsuarioTienda(@Body Usuario usuario);

    @GET("list/usuario/{idTienda}")
    Call<List<Usuario>> listarUsuarios(@Path("idTienda") int idTienda, @Query("token") String token);

    @GET("view/usuario/{idUsuario}")
    Call<Usuario> verUsuario(@Path("idUsuario") int idUsuario, @Query("token") String token);

    @POST("insert/usuario")
    Call<Usuario> crearUsuario(@Body Usuario usuario, @Query("token") String token);

    @DELETE("delete/usuario/{idUsuario}")
    Call<Usuario> eliminarUsuario(@Path("idUsuario") int idUsuario, @Query("token") String token);

    @POST("modify/usuario/{idUsuario}")
    Call<Usuario> modificarUsuario(@Path("idUsuario") int idUsuario, @Body Usuario usuario,  @Query("token") String token );

    @POST("modify/perfil/{idUsuario}")
    Call<Usuario> modificarPerfil(@Path("idUsuario") int idUsuario, @Body Usuario usuario,  @Query("token") String token );

    void verPerfil(int idUsuario);

}
