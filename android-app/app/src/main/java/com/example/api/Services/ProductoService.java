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

/**
 * Clase que llama a la clase ProductoController de la API
 */
public interface ProductoService {

    /**
     * Lista los productos de una tienda
     * @param idTienda int Determina el id de la tienda a la que pertenecen los productos
     * @param token String Determina si el usuario está logado
     * @return List<Producto>
     */
    @GET("list/producto/{idTienda}")
    Call<List<Producto>> listarProductos(@Path("idTienda") int idTienda, @Query("token") String token);

    /**
     * Devuelve los datos de un producto
     * @param idProducto int Determina de que producto saca los datos
     * @param token String Determina si el usuario está logado
     * @return Producto
     */
    @GET("view/producto/{idProducto}")
    Call<Producto> verProducto(@Path("idProducto") int idProducto, @Query("token") String token);

    /**
     * Crea un producto
     * @param producto Producto Determina el producto que se crea en la tabla Producto
     * @param token String Determina si el usuario está logado
     * @return Producto
     */
    @POST("insert/producto")
    Call<Producto> crearProducto(@Body Producto producto, @Query("token") String token);

    /**
     * Modifica un producto
     * @param idProducto int Determina el id del producto a modificar
     * @param producto Producto Determina los nuevos datos del producto en un nuevo objeto Producto
     * @param token String Determina si el usuario está logado
     * @return Producto
     */
    @POST("modify/producto/{idProducto}")
    Call<Producto> modificarProducto(@Path("idProducto") int idProducto, @Body Producto producto, @Query("token") String token );

    /**
     * Elimina un producto
     * @param idProducto int Determina el id del producto a borrar
     * @param token String Determina si el usuario está logado
     * @return Producto
     */
    @DELETE("delete/producto/{idProducto}")
    Call<Producto> eliminarProducto(@Path("idProducto") int idProducto, @Query("token") String token);

}
