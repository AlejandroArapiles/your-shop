package com.example.yourshop;

import android.content.Context;
import android.content.SharedPreferences;
import android.text.format.DateFormat;

import com.example.api.Entity.Sesion;
import com.example.api.Entity.Tienda;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;
import java.util.concurrent.TimeUnit;

public class ManageSharedPreferences {

    public static final String SESION = "sesion";
    private SharedPreferences sharedPreferences;

    public ManageSharedPreferences(Context context) {
        sharedPreferences = context.getSharedPreferences(SESION, 0);
    }

    public void guardarSesion(Sesion sesion) {
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt("idusuario", sesion.getIdusuarioFk().getIdusuario());
        editor.putString("nombreusuario", sesion.getIdusuarioFk().getNombreusuario());
        editor.putString("rol", sesion.getIdusuarioFk().getRol());
        setTienda(sesion.getIdusuarioFk().getIdtiendaFk());
        editor.putString("token", sesion.getSesion());
        editor.putLong("sesionIniciada", System.currentTimeMillis());
        editor.putString("lastlogin", sesion.getLastLogin());
        editor.apply();
    }

    public void cerrarSesion() {
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
    }

    public boolean isSesionIniciada() {
        if (sharedPreferences.getInt("idusuario", 0) != 0) {
            long loginDate = sharedPreferences.getLong("sesionIniciada", System.currentTimeMillis());
            long diff = System.currentTimeMillis() - loginDate;
            if (TimeUnit.HOURS.convert(diff, TimeUnit.MILLISECONDS) > 10) {
                cerrarSesion();
                return false;
            }
            return true;
        }
        return false;
    }

    public String getNombreUsuario(){
        return sharedPreferences.getString("nombreusuario", "");
    }

    public String getLastLogin() {
        try {
            SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ssXXX", Locale.getDefault());
            Date date = dateFormat.parse(sharedPreferences.getString("lastlogin", ""));
            dateFormat = new SimpleDateFormat("HH:mm:ss dd-MM-yyyy", Locale.getDefault());
            dateFormat.setTimeZone(TimeZone.getTimeZone("Europe/Madrid"));
            return dateFormat.format(date);
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return sharedPreferences.getString("lastlogin", "");
    }

    public void setTienda(Tienda tienda) {
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt("idtienda", tienda.getIdtienda());
        editor.putString("nombretienda", tienda.getNombretienda());
        editor.putString("cif", tienda.getCif());
        editor.putString("correocontacto", tienda.getCorreocontacto());
        editor.apply();
    }

    public int getIdTienda() {
        return sharedPreferences.getInt("idtienda", 0);
    }

    public Tienda getTienda() {
        Tienda tienda = new Tienda();
        tienda.setIdtienda(sharedPreferences.getInt("idtienda", 0));
        tienda.setNombretienda(sharedPreferences.getString("nombretienda", "0"));
        tienda.setCif(sharedPreferences.getString("cif", "0"));
        tienda.setCorreocontacto(sharedPreferences.getString("correocontacto", "0"));
        return tienda;
    }


    public int getIdUsuario(){
        return sharedPreferences.getInt("idusuario", 0);
    }

    public String getToken() {
        return sharedPreferences.getString("token", "");
    }

    public boolean isAdmin() {
        return sharedPreferences.getString("rol", "").equalsIgnoreCase("admin");
    }
}
