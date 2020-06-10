package com.example.api.Services;

import com.example.api.Entity.Producto;
import com.example.api.Entity.Usuario;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.DELETE;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Path;
import retrofit2.http.Query;

public interface ProductoService {


    @GET("list/producto/{idTienda}")
    Call<List<Producto>> listarProductos(@Path("idTienda") int idTienda, @Query("token") String token);

    @GET("view/producto/{idProducto}")
    Call<Producto> verProducto(@Path("idProducto") int idProducto, @Query("token") String token);

    @POST("insert/producto")
    Call<Producto> crearProducto(@Body Producto producto, @Query("token") String token);

    @POST("modify/producto/{idProducto}")
    Call<Producto> modificarProducto(@Path("idProducto") int idProducto, @Body Producto producto, @Query("token") String token );

    @DELETE("delete/producto/{idProducto}")
    Call<Producto> eliminarProducto(@Path("idProducto") int idProducto, @Query("token") String token);

}
