package com.example.api.Services;

import com.example.api.Entity.Tienda;
import com.example.api.Entity.Usuario;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.POST;
import retrofit2.http.Path;
import retrofit2.http.Query;

public interface TiendaService {


    @POST("modify/tienda/{idTienda}")
    Call<Tienda> modificarTienda(@Path("idTienda") int idTienda, @Body Tienda tienda, @Query("token") String token );
}
