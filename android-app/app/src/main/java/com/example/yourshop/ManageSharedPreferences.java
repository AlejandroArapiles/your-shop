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
/**
 * Clase que gestiona la sesion en el SharedPreferences
 */
public class ManageSharedPreferences {

    /**
     * Atributos de ManageSharedPreferences
     */
    public static final String SESION = "sesion";
    private SharedPreferences sharedPreferences;

    /**
     * Inicializa el objeto sharedPreferences
     * @param context Context Contexto de la aplicación
     */
    public ManageSharedPreferences(Context context) {
        sharedPreferences = context.getSharedPreferences(SESION, 0);
    }

    /**
     * Guarda la sesión en el SharedPreferences
     * @param sesion Sesion Objeto Sesion con los datos de la misma
     */
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

    /**
     * Elimina los datos de la sesión
     */
    public void cerrarSesion() {
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();
    }

    /**
     * Comprueba si la sesión está iniciada, actualizando además el lastlogin de este
     * @return boolean Devuelve true si la sesión está iniciada sino false
     */
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

    /**
     * Devuelve el nombre del usuario de la sesión
     * @return String
     */
    public String getNombreUsuario(){
        return sharedPreferences.getString("nombreusuario", "");
    }

    /**
     * Devuelve el lastlogin del usuario a la aplicación
     * @return String
     */
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

    /**
     * Actualiza los datos de la tienda del usuario en el SharedPreferences
     * @param tienda Tienda Objeto Tienda con sus nuevos datos
     */
    public void setTienda(Tienda tienda) {
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putInt("idtienda", tienda.getIdtienda());
        editor.putString("nombretienda", tienda.getNombretienda());
        editor.putString("cif", tienda.getCif());
        editor.putString("correocontacto", tienda.getCorreocontacto());
        editor.apply();
    }

    /**
     * Devuelve el id de la tienda a la que pertenece el usuario de la sesión
     * @return int
     */
    public int getIdTienda() {
        return sharedPreferences.getInt("idtienda", 0);
    }

    /**
     * Devuelve la tienda a la que pertenece el usuario de la sesión
     * @return Tienda
     */
    public Tienda getTienda() {
        Tienda tienda = new Tienda();
        tienda.setIdtienda(sharedPreferences.getInt("idtienda", 0));
        tienda.setNombretienda(sharedPreferences.getString("nombretienda", "0"));
        tienda.setCif(sharedPreferences.getString("cif", "0"));
        tienda.setCorreocontacto(sharedPreferences.getString("correocontacto", "0"));
        return tienda;
    }

    /**
     * Devuelve el id del usuario de la sesión
     * @return int
     */
    public int getIdUsuario(){
        return sharedPreferences.getInt("idusuario", 0);
    }

    /**
     * Devuelve el MD5 de la sesión
     * @return String
     */
    public String getToken() {
        return sharedPreferences.getString("token", "");
    }

    /**
     * Comprueba si el usuario logado es admin o emple
     * @return boolean Devuelve true si es administrador sino false
     */
    public boolean isAdmin() {
        return sharedPreferences.getString("rol", "").equalsIgnoreCase("admin");
    }
}
