package com.example.api.Services;

import com.example.api.Entity.Sesion;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.POST;

public interface SesionService {

    @POST("login")
    public Call<Sesion> iniciarSesion(@Body UserLogin userLogin);
    public void cerrarSesion();
}
