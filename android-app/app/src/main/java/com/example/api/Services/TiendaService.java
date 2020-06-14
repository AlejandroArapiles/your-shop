package com.example.api.Services;

import com.example.api.Entity.Tienda;
import com.example.api.Entity.Usuario;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.POST;
import retrofit2.http.Path;
import retrofit2.http.Query;

/**
 * Clase que llama a la clase TiendaController de la API
 */
public interface TiendaService {

    /**
     * Modifica la tienda a la que pertenece el usuario
     * @param idTienda int Determina el id de la tienda a modificar
     * @param tienda Tienda Objeto Tienda con los nuevos datos de la tienda
     * @param token String Determina si el usuario está logado
     * @return Tienda
     */
    @POST("modify/tienda/{idTienda}")
    Call<Tienda> modificarTienda(@Path("idTienda") int idTienda, @Body Tienda tienda, @Query("token") String token );
}
