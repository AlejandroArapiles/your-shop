package com.example.yourshop;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;

import com.example.api.Entity.Producto;
import com.example.api.Entity.Sesion;
import com.example.api.Entity.Tienda;
import com.example.api.Entity.Usuario;
import com.example.api.Services.ProductoService;
import com.example.api.Services.SesionService;
import com.example.api.Services.TiendaService;
import com.example.api.Services.UserLogin;
import com.example.api.Services.UsuarioService;

import java.util.ArrayList;
import java.util.List;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.jackson.JacksonConverterFactory;

/**
 * Clase que contiene todas las llamadas a los servicios
 */
public class MainActivity extends AppCompatActivity {

    /**
     * Atributos y objetos de la clase
     */
    boolean admin;
    ViewFlipper flipper;
    ProgressBar progressBar;
    UsuarioService usuarioService;
    ProductoService productoService;
    SesionService sesionService;
    TiendaService tiendaService;
    ListView lvUsuarios;
    ArrayList<Usuario> listaUsuarios;
    ArrayAdapter<Usuario> adapterUsuarios;
    ManageSharedPreferences preferences;
    ListView lvProductos;
    ArrayList<Producto> listaProductos;
    ArrayAdapter<Producto> adapterProductos;
    ActionBar actionBar;
    int idCurrentUser;
    int idCurrentProduct;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.pantalla_principal);
        flipper = findViewById(R.id.flipperVentanas);
        actionBar = getSupportActionBar();

        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://your-shop.walpurgis.es/api/")
                .addConverterFactory(JacksonConverterFactory.create())
                .build();

        usuarioService = retrofit.create(UsuarioService.class);
        productoService = retrofit.create(ProductoService.class);
        sesionService = retrofit.create(SesionService.class);
        tiendaService = retrofit.create(TiendaService.class);
        configurarListadoUsuarios();
        configurarListadoProductos();

        preferences = new ManageSharedPreferences(this);
        if (preferences.isSesionIniciada()) {
            listarProductos();
            admin = preferences.isAdmin();
        }
    }

    @Override
    public boolean onPrepareOptionsMenu(Menu menu) {
        super.onPrepareOptionsMenu(menu);
        menu.clear();
        if (!preferences.isSesionIniciada()) {
            return false;
        }

        if (admin) {
            MenuInflater inflater = getMenuInflater();
            inflater.inflate(R.menu.menuadmin, menu);
            return true;
        } else {
            MenuInflater inflater = getMenuInflater();
            inflater.inflate(R.menu.menuemple, menu);
            TextView tvPregunta2 = findViewById(R.id.helpPregunta2);
            tvPregunta2.setVisibility(View.GONE);
            TextView tvRespuesta2 = findViewById(R.id.helpRespuesta2);
            tvRespuesta2.setVisibility(View.GONE);
            TextView tvPregunta4 = findViewById(R.id.helpPregunta4);
            tvPregunta4.setVisibility(View.GONE);
            TextView tvRespuesta4 = findViewById(R.id.helpRespuesta4);
            tvRespuesta4.setVisibility(View.GONE);
            return true;
        }

    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
            //Llamadas a los metodos en función de las opciones del menú
            switch (id) {
                case R.id.menuListarProductos:
                    listarProductos();
                    break;
                case R.id.menuCrearProducto:
                    vaciarFormularioProducto();
                    break;
                case R.id.menuModificarTienda:
                    rellenarFormularioMT();
                    break;
                case R.id.menuListarUsuarios:
                    listarUsuarios();
                    break;
                case R.id.menuCrearUsuario:
                    vaciarFormularioUsuario();
                    break;
                case R.id.menuModificarPerfil:
                    rellenarModificarPerfil();
                    break;
                case R.id.menuAyuda:
                    flipper.setDisplayedChild(10);
                    break;
                case R.id.menuCerrarSesion:
                    cerrarSesion();
                    break;
                case android.R.id.home:
                    flipper.setDisplayedChild(0);
                    actionBar.setDisplayHomeAsUpEnabled(false);
            }

        return true;
    }

    /**
     * Inicializa los objetos para el listado de productos y añade evento onclick
     */
    private void configurarListadoProductos() {
        lvProductos = findViewById(R.id.lvProductos);
        listaProductos = new ArrayList<>();
        adapterProductos = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, listaProductos);
        lvProductos.setAdapter(adapterProductos);
        lvProductos.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Producto producto = listaProductos.get(position);
                EditText etNombre = findViewById(R.id.etNombreProductoMP);
                EditText etDescripcion = findViewById(R.id.etDescripcionProductoMP);
                EditText etCantidad = findViewById(R.id.etCantidadMP);
                EditText etPrecio = findViewById(R.id.etPrecioMP);
                Switch swActivo = findViewById(R.id.swActivoMP);
                etNombre.setText(producto.getNombreproducto());
                etDescripcion.setText(producto.getDescripcion());
                etCantidad.setText(String.valueOf(producto.getCantidad()));
                etPrecio.setText(String.valueOf(producto.getPrecio()));
                swActivo.setChecked(producto.isActivo());
                idCurrentProduct = producto.getIdproducto();
                flipper.setDisplayedChild(3);
            }
        });
    }

    /**
     * Recoge todos los productos y los muestra en el listview de lvProductos
     */
    private void listarProductos() {
        flipper.setDisplayedChild(1);
        progressBar = findViewById(R.id.pBarProducto);
        Call<List<Producto>> request = productoService.listarProductos(preferences.getIdTienda(), preferences.getToken());
        listaProductos.clear();
        progressBar.setVisibility(View.VISIBLE);
        request.enqueue(new Callback<List<Producto>>() {
            @Override
            public void onResponse(Call<List<Producto>> call, Response<List<Producto>> response) {
                progressBar.setVisibility(View.GONE);
                if (response.isSuccessful() && response.body() != null) {
                    List<Producto> productos = response.body();
                    listaProductos.addAll(productos);
                    adapterProductos.notifyDataSetChanged();
                }  else {
                    Toast.makeText(MainActivity.this, R.string.errorListado, Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<List<Producto>> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                Log.e("api", t.getMessage(), t);
                Toast.makeText(MainActivity.this, R.string.errorListado, Toast.LENGTH_LONG).show();
            }
        });
    }

    /**
     * Vacía los campos del formulario Producto > Crear producto
     */
    private void vaciarFormularioProducto() {
        EditText etNombre = findViewById(R.id.etNombreProducto);
        etNombre.setEnabled(true);
        etNombre.setText("");
        EditText etDescripcion = findViewById(R.id.etDescripcionProducto);
        etDescripcion.setEnabled(true);
        etDescripcion.setText("");
        EditText etCantidad = findViewById(R.id.etCantidad);
        etCantidad.setEnabled(true);
        etCantidad.setText("");
        EditText etPrecio = findViewById(R.id.etPrecio);
        etPrecio.setEnabled(true);
        etPrecio.setText("");
        Switch swActivo = findViewById(R.id.swActivo);
        swActivo.setEnabled(true);
        swActivo.setChecked(false);
        Button bCrearProducto = findViewById(R.id.bCrearProducto);
        bCrearProducto.setVisibility(View.VISIBLE);
        flipper.setDisplayedChild(2);
    }

    /**
     * Cambia la vista al listado de productos
     * @param v View Botón que hace la llamada
     */
    public void cambiarVistaListaProductos(View v){
        listarProductos();
    }

    /**
     * Crea un producto
     * @param v View Botón que hace la llamada
     */
    public void crearProducto(View v){
        EditText etNombre = findViewById(R.id.etNombreProducto);
        EditText etDescripcion = findViewById(R.id.etDescripcionProducto);
        EditText etPrecio = findViewById(R.id.etPrecio);
        EditText etCantidad = findViewById(R.id.etCantidad);
        Switch sw= findViewById(R.id.swActivo);

        //Comprobaciones de que los campos sean válidos
        if(!etNombre.getText().toString().isEmpty() && !etDescripcion.getText().toString().isEmpty() && !etCantidad.getText().toString().isEmpty() && !etPrecio.getText().toString().isEmpty()){
            Producto producto = new Producto();
            Tienda tienda = new Tienda();
            producto.setNombreproducto(etNombre.getText().toString());
            producto.setDescripcion(etDescripcion.getText().toString());
            producto.setCantidad(Integer.parseInt(etCantidad.getText().toString()));
            producto.setPrecio(Double.parseDouble(etPrecio.getText().toString()));
            producto.setActivo(sw.isChecked());
            tienda.setIdtienda(preferences.getIdTienda());
            producto.setIdtiendaFk(tienda);
            Call<Producto> request = productoService.crearProducto(producto, preferences.getToken());
            request.enqueue(new Callback<Producto>() {
                @Override
                public void onResponse(Call<Producto> call, Response<Producto> response) {
                    if (response.isSuccessful()) {
                        Toast.makeText(MainActivity.this, R.string.crearProductoOK, Toast.LENGTH_SHORT).show();
                        listarProductos();
                    }  else {
                        Toast.makeText(MainActivity.this, R.string.errorCrear, Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Producto> call, Throwable t) {
                    Toast.makeText(MainActivity.this, R.string.errorCrear, Toast.LENGTH_LONG).show();
                }
            });
        } else {
            Toast.makeText(MainActivity.this, R.string.errorCampoVacio, Toast.LENGTH_LONG).show();
        }
    }

    /**
     * Modifica un producto
     * @param v View Botón que hace la llamada
     */
    public void modificarProducto(View v){

        EditText etNombre = findViewById(R.id.etNombreProductoMP);
        EditText etDescripcion = findViewById(R.id.etDescripcionProductoMP);
        EditText etCantidad = findViewById(R.id.etCantidadMP);
        EditText etPrecio = findViewById(R.id.etPrecioMP);
        Switch swActivo = findViewById(R.id.swActivoMP);
        if(!etNombre.getText().toString().isEmpty() && !etDescripcion.getText().toString().isEmpty() && !etCantidad.getText().toString().isEmpty() && !etPrecio.getText().toString().isEmpty()){
            Producto producto = new Producto();
            producto.setNombreproducto(etNombre.getText().toString());
            producto.setDescripcion(etDescripcion.getText().toString());
            producto.setPrecio(Double.parseDouble(etPrecio.getText().toString()));
            producto.setCantidad(Integer.parseInt(etCantidad.getText().toString()));
            producto.setActivo(swActivo.isChecked());
            Call<Producto> request = productoService.modificarProducto(preferences.getIdTienda(),producto,  preferences.getToken());
            request.enqueue(new Callback<Producto>() {
                @Override
                public void onResponse(Call<Producto> call, Response<Producto> response) {
                    if (response.isSuccessful()) {
                        Toast.makeText(MainActivity.this, R.string.modificarProductoOK, Toast.LENGTH_SHORT).show();
                        listarProductos();
                    }  else {
                        Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Producto> call, Throwable t) {
                    Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                }
            });
        } else {
            Toast.makeText(MainActivity.this, R.string.errorCampoVacio, Toast.LENGTH_LONG).show();
        }
    }

    /**
     * Muestra el diálogo que confirma el borrado de un producto
     * @param v View Botón que hace la llamada
     */
    public void abrirDialogEliminarProducto(View v){
        AlertDialog.Builder builder1 = new AlertDialog.Builder(this);
        builder1.setMessage(R.string.dialogProducto);
        builder1.setCancelable(true);

        builder1.setPositiveButton(
                R.string.si,
                (dialog, id12) -> {
                    Call<Producto> request = productoService.eliminarProducto(idCurrentProduct,  preferences.getToken());
                    request.enqueue(new Callback<Producto>() {
                        @Override
                        public void onResponse(Call<Producto> call, Response<Producto> response) {
                            if (response.isSuccessful()) {
                                Toast.makeText(MainActivity.this, R.string.eliminarProductoOk, Toast.LENGTH_SHORT).show();
                            }  else {
                                Toast.makeText(MainActivity.this, R.string.errorEliminar, Toast.LENGTH_LONG).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Producto> call, Throwable t) {
                            Toast.makeText(MainActivity.this, R.string.errorEliminar, Toast.LENGTH_LONG).show();
                        }
                    });

                });

        builder1.setNegativeButton(
                R.string.no,
                (dialog, id1) -> dialog.cancel());

        AlertDialog alerta = builder1.create();
        alerta.show();
    }

    /**
     * Rellena el formulario de modificar con los datos de la tienda a la que pertenece el usuario
     */
    public void rellenarFormularioMT(){
        Tienda tienda = preferences.getTienda();
        EditText etNombre = findViewById(R.id.etNombreTiendaM);
        EditText etCif = findViewById(R.id.etCIFM);
        EditText etCorreo = findViewById(R.id.etCorreoM);
        etNombre.setText(tienda.getNombretienda());
        etCif.setText(tienda.getCif());
        etCorreo.setText(tienda.getCorreocontacto());
        flipper.setDisplayedChild(4);
    }

    /**
     * Modifica la tienda a la que pertenece el usuario
     * @param v View Botón que hace la llamada
     */
    public void modificarTienda(View v){
        EditText etNombre = findViewById(R.id.etNombreTiendaM);
        EditText etCif = findViewById(R.id.etCIFM);
        EditText etCorreo = findViewById(R.id.etCorreoM);

        if(!etNombre.getText().toString().isEmpty() && !etCif.getText().toString().isEmpty()  && !etCorreo.getText().toString().isEmpty()){
            Tienda tienda = new Tienda();
            tienda.setIdtienda(preferences.getIdTienda());
            tienda.setNombretienda(etNombre.getText().toString());
            tienda.setCif(etCif.getText().toString());
            tienda.setCorreocontacto(etCorreo.getText().toString());
            preferences.setTienda(tienda);
            Call<Tienda> request = tiendaService.modificarTienda(tienda.getIdtienda(), tienda, preferences.getToken());
            request.enqueue(new Callback<Tienda>() {
                @Override
                public void onResponse(Call<Tienda> call, Response<Tienda> response) {
                    if (response.isSuccessful()) {
                        Toast.makeText(MainActivity.this, R.string.modificarTiendaOK, Toast.LENGTH_SHORT).show();
                        listarUsuarios();
                    }  else {
                        Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Tienda> call, Throwable t) {
                    Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                }

            });
        } else {
            Toast.makeText(MainActivity.this, R.string.errorCampoVacio, Toast.LENGTH_LONG).show();
        }

    }

    /**
     * Inicializa los objetos para el listado de productos y añade evento onclick
     */
    private void configurarListadoUsuarios() {
        lvUsuarios = findViewById(R.id.lvUsuarios);
        listaUsuarios = new ArrayList<>();
        adapterUsuarios = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, listaUsuarios);
        lvUsuarios.setAdapter(adapterUsuarios);
        Spinner spRol = findViewById(R.id.spRol);
        ArrayAdapter<CharSequence> adapter = ArrayAdapter.createFromResource(this,
                R.array.spinnerUsuario, android.R.layout.simple_spinner_item);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spRol.setAdapter(adapter);
        Spinner spModificarRol = findViewById(R.id.spRolMU);
        spModificarRol.setAdapter(adapter);
        lvUsuarios.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Usuario usuario = listaUsuarios.get(position);
                EditText etNombre = findViewById(R.id.etNombreMU);
                etNombre.setText(usuario.getNombreusuario());
                Spinner spRol = findViewById(R.id.spRolMU);
                spRol.setSelection((usuario.getRol().equalsIgnoreCase("emple") ? 0 : 1));
                idCurrentUser = usuario.getIdusuario();
                flipper.setDisplayedChild(7);
            }
        });
    }

    /**
     * Recoge todos los usuarios y los muestra en el listview de lvUsuarios (solo admin)
     */
    private void listarUsuarios() {
        flipper.setDisplayedChild(5);
        progressBar = findViewById(R.id.pBarUsuario);
        Call<List<Usuario>> request = usuarioService.listarUsuarios(preferences.getIdTienda(), preferences.getToken());
        listaUsuarios.clear();
        progressBar.setVisibility(View.VISIBLE);
        request.enqueue(new Callback<List<Usuario>>() {
            @Override
            public void onResponse(Call<List<Usuario>> call, Response<List<Usuario>> response) {
                progressBar.setVisibility(View.GONE);
                if (response.isSuccessful() && response.body() != null) {
                    List<Usuario> usuarios = response.body();
                    listaUsuarios.addAll(usuarios);
                    adapterUsuarios.notifyDataSetChanged();
                }  else {
                    Toast.makeText(MainActivity.this, R.string.errorListado, Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<List<Usuario>> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                Toast.makeText(MainActivity.this, R.string.errorListado, Toast.LENGTH_LONG).show();
            }

        });
    }

    /**
     * Vacía todos los campos del formulario Usuario > Crear usuario (solo admin)
     */
    private void vaciarFormularioUsuario() {
        EditText etNombre = findViewById(R.id.etNombreUsuario);
        etNombre.setEnabled(true);
        etNombre.setText("");
        EditText etPassword = findViewById(R.id.etPassword);
        TextView tvPassword = findViewById(R.id.tvPassword);
        etPassword.setText("");
        etPassword.setVisibility(View.VISIBLE);
        tvPassword.setVisibility(View.VISIBLE);
        Spinner spRol = findViewById(R.id.spRol);
        spRol.setEnabled(true);
        Button bCrearUsuario = findViewById(R.id.bCrearUsuario);
        bCrearUsuario.setVisibility(View.VISIBLE);
        flipper.setDisplayedChild(6);
    }

    /**
     * Cambia la vista al listado de productos
     * @param v View Botón que hace la llamada
     */
    public void cambiarVistaListaUsuarios(View v){
        listarUsuarios();
    }

    /**
     * Crea un usuario
     * @param v View Botón que hace la llamada
     */
    public void crearUsuario(View v) {
        EditText etNombre = findViewById(R.id.etNombreUsuario);
        EditText etPassword = findViewById(R.id.etPassword);
        Spinner spRol = findViewById(R.id.spRol);
        if(!etNombre.getText().toString().isEmpty() && !etPassword.getText().toString().isEmpty()){
            Tienda tienda = new Tienda();
            tienda.setIdtienda(preferences.getIdTienda());
            Usuario usuario = new Usuario();
            usuario.setIdtiendaFk(tienda);
            usuario.setNombreusuario(etNombre.getText().toString());
            usuario.setPassword(etPassword.getText().toString());
            usuario.setRol(spRol.getSelectedItem().toString().substring(0,5).toLowerCase());
            Call<Usuario> request = usuarioService.crearUsuario(usuario, preferences.getToken());
            request.enqueue(new Callback<Usuario>() {
                @Override
                public void onResponse(Call<Usuario> call, Response<Usuario> response) {
                    if (response.isSuccessful()) {
                        Toast.makeText(MainActivity.this, R.string.crearUsuarioOK, Toast.LENGTH_SHORT).show();
                        listarUsuarios();
                    }  else {
                        Toast.makeText(MainActivity.this, R.string.errorCrear, Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Usuario> call, Throwable t) {
                    Toast.makeText(MainActivity.this, R.string.errorCrear, Toast.LENGTH_LONG).show();
                }

            });
        } else {
            Toast.makeText(MainActivity.this, R.string.errorCampoVacio, Toast.LENGTH_LONG).show();
        }

    }

    /**
     * Modifica un usuario
     * @param v View Botón que hace la llamada (solo admin)
     */
    public void modificarUsuario(View v){
        EditText etNombre = findViewById(R.id.etNombreMU);
        EditText etPassword = findViewById(R.id.etPasswordMU);
        Spinner spRol = findViewById(R.id.spRolMU);
        Usuario usuario = new Usuario();
        usuario.setIdusuario(idCurrentUser);
        usuario.setNombreusuario(etNombre.getText().toString());
        if(!etPassword.getText().toString().isEmpty()){
            usuario.setPassword(etPassword.getText().toString());
        }
        usuario.setRol((spRol.getSelectedItem().toString().substring(0,5).toLowerCase()));
        if(!etNombre.getText().toString().isEmpty()){
            Call<Usuario> request = usuarioService.modificarUsuario(usuario.getIdusuario(),usuario,  preferences.getToken());
            request.enqueue(new Callback<Usuario>() {
                @Override
                public void onResponse(Call<Usuario> call, Response<Usuario> response) {
                    if (response.isSuccessful()) {
                        Toast.makeText(MainActivity.this, R.string.modificarUsuarioOK, Toast.LENGTH_SHORT).show();
                        listarUsuarios();
                    }  else {
                        Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Usuario> call, Throwable t) {
                    Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                }

            });
        }

    }

    /**
     * Muestra el diálogo que confirma el borrado de un usuario
     * @param v View Botón que hace la llamada
     */
    public void abrirDialogEliminarUsuario(View v){
        AlertDialog.Builder builder1 = new AlertDialog.Builder(this);
        builder1.setMessage(R.string.dialogUsuario);
        builder1.setCancelable(true);

        builder1.setPositiveButton(
                R.string.si,
                (dialog, id12) -> {
                    Call<Usuario> request = usuarioService.eliminarUsuario(idCurrentUser,  preferences.getToken());
                    request.enqueue(new Callback<Usuario>() {
                        @Override
                        public void onResponse(Call<Usuario> call, Response<Usuario> response) {
                            if (response.isSuccessful()) {
                                Toast.makeText(MainActivity.this, R.string.eliminarUsuarioOK, Toast.LENGTH_SHORT).show();
                            }  else {
                                Toast.makeText(MainActivity.this, R.string.errorEliminar, Toast.LENGTH_LONG).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Usuario> call, Throwable t) {
                            Toast.makeText(MainActivity.this, R.string.errorEliminar, Toast.LENGTH_LONG).show();
                        }
                    });

                });

        builder1.setNegativeButton(
                R.string.no,
                (dialog, id1) -> dialog.cancel());

        AlertDialog alerta = builder1.create();
        alerta.show();
    }

    /**
     * Rellena el formulario de modificar perfil con el nombre de usuario y su last login
     */
    public void rellenarModificarPerfil(){
        EditText etNombre = findViewById(R.id.etNombreMP);
        EditText etLastLogin = findViewById(R.id.etLastLoginMP);
        EditText etContraseña = findViewById(R.id.etPasswordMP);
        EditText etRepetirContraseña = findViewById(R.id.etRepeatPasswordMP);
        etContraseña.setText("");
        etRepetirContraseña.setText("");
        etNombre.setText(preferences.getNombreUsuario());
        etLastLogin.setText(preferences.getLastLogin());
        flipper.setDisplayedChild(8);
    }

    /**
     * Modifica la contraseña del usuario logado
     * @param v View Botón que hace la llamada
     */
    public void modificarPerfil(View v){
        EditText etContraseña = findViewById(R.id.etPasswordMP);
        EditText etRepetirContraseña = findViewById(R.id.etRepeatPasswordMP);

        if(!etContraseña.getText().toString().isEmpty() && !etRepetirContraseña.getText().toString().isEmpty()){
            if(etContraseña.getText().toString().equals(etRepetirContraseña.getText().toString())){
                Usuario usuario = new Usuario();
                usuario.setPassword(etContraseña.getText().toString());
                Call<Usuario> request = usuarioService.modificarPerfil(preferences.getIdUsuario(), usuario, preferences.getToken());
                request.enqueue(new Callback<Usuario>() {
                    @Override
                    public void onResponse(Call<Usuario> call, Response<Usuario> response) {
                        if (response.isSuccessful()) {
                            Toast.makeText(MainActivity.this, R.string.modificarUsuarioOK, Toast.LENGTH_SHORT).show();
                        }  else {
                            Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<Usuario> call, Throwable t) {
                        Toast.makeText(MainActivity.this, R.string.errorModificar, Toast.LENGTH_LONG).show();
                    }
                });
            } else {
                Toast.makeText(MainActivity.this, R.string.errorContraseña, Toast.LENGTH_LONG).show();
            }
        } else {
            Toast.makeText(MainActivity.this, R.string.errorCampoVacio, Toast.LENGTH_LONG).show();
        }
    }

    /**
     * Inicia sesión en la aplicación habilitando todas las opciones
     * @param v View Botón que hace la llamada
     */
    public void iniciarSesion(View v) {
        EditText etUsuario = findViewById(R.id.etUsuario);
        EditText etPassword = findViewById(R.id.etPasswordLogin);
        UserLogin userLogin = new UserLogin();
        userLogin.setUsuario(etUsuario.getText().toString());
        userLogin.setPassword(etPassword.getText().toString());
        Call<Sesion> request = sesionService.iniciarSesion(userLogin);
        request.enqueue(new Callback<Sesion>() {
            @Override
            public void onResponse(Call<Sesion> call, Response<Sesion> response) {
                if (response.isSuccessful() && response.body() != null) {
                    preferences.guardarSesion(response.body());
                    admin = preferences.isAdmin();
                    invalidateOptionsMenu();
                    listarProductos();
                } else {
                    Toast.makeText(MainActivity.this, R.string.errorLogin, Toast.LENGTH_LONG).show();
                    etUsuario.setText("");
                    etPassword.setText("");
                }
            }

            @Override
            public void onFailure(Call<Sesion> call, Throwable t) {
                Toast.makeText(MainActivity.this, R.string.errorLoginServidor, Toast.LENGTH_LONG).show();
            }
        });


    }

    /**
     * Borra y cierra la sesión del usuario en la aplicación
     */
    private void cerrarSesion() {
        AlertDialog.Builder builder1 = new AlertDialog.Builder(this);
        builder1.setMessage(R.string.dialogCerrarSesion);
        builder1.setCancelable(true);

        builder1.setPositiveButton(
                R.string.si,
                (dialog, id12) -> {
                    Sesion sesion = new Sesion();
                    sesion.setSesion(preferences.getToken());
                    Call<ResponseBody> request = sesionService.cerrarSesion(sesion);
                    request.enqueue(new Callback<ResponseBody>() {
                        @Override
                        public void onResponse(Call<ResponseBody> call, Response<ResponseBody> response) {
                            if (response.isSuccessful()) {
                                flipper.setDisplayedChild(0);
                                preferences.cerrarSesion();
                                invalidateOptionsMenu();
                                EditText etUsuario = findViewById(R.id.etUsuario);
                                EditText etPassword = findViewById(R.id.etPasswordLogin);
                                etUsuario.setText("");
                                etPassword.setText("");
                            }
                        }

                        @Override
                        public void onFailure(Call<ResponseBody> call, Throwable t) {
                            Toast.makeText(MainActivity.this, R.string.errorLogout, Toast.LENGTH_LONG).show();
                        }
                    });

                });

        builder1.setNegativeButton(
                R.string.no,
                (dialog, id1) -> dialog.cancel());

        AlertDialog alerta = builder1.create();
        alerta.show();
    }

    /**
     * Abre el formulario de registro vaciando todos los campos a su vez
     * @param v View Botón que hace la llamada
     */
    public void abrirRegistroForm(View v){
        actionBar.setDisplayHomeAsUpEnabled(true);
        EditText etNombreusuario = findViewById(R.id.etNombreUsuarioRegistro);
        EditText etContraseña = findViewById(R.id.etPasswordRegistro);
        EditText etNombretienda = findViewById(R.id.etNombreTiendaRegistro);
        EditText etCIF = findViewById(R.id.etCIFRegistro);
        EditText etCorreo = findViewById(R.id.etCorreoRegistro);
        etNombreusuario.setText("");
        etContraseña.setText("");
        etNombretienda.setText("");
        etCIF.setText("");
        etCorreo.setText("");
        flipper.setDisplayedChild(9);
    }

    /**
     * Registra una nueva tienda en la aplicación así como un usuario admin
     * @param v
     */
    public void registrar(View v){
        EditText etNombreusuario = findViewById(R.id.etNombreUsuarioRegistro);
        EditText etContraseña = findViewById(R.id.etPasswordRegistro);
        EditText etNombretienda = findViewById(R.id.etNombreTiendaRegistro);
        EditText etCIF = findViewById(R.id.etCIFRegistro);
        EditText etCorreo = findViewById(R.id.etCorreoRegistro);
        if(!etNombreusuario.getText().toString().isEmpty() && !etContraseña.getText().toString().isEmpty()
                && !etNombretienda.getText().toString().isEmpty() && !etCorreo.getText().toString().isEmpty()
                && !etCIF.getText().toString().isEmpty()) {
            Tienda tienda = new Tienda();
            tienda.setNombretienda(etNombretienda.getText().toString());
            tienda.setCorreocontacto(etCorreo.getText().toString());
            tienda.setCif(etCIF.getText().toString());

            Usuario usuario = new Usuario();
            usuario.setNombreusuario(etNombreusuario.getText().toString());
            usuario.setPassword(etContraseña.getText().toString());
            usuario.setIdtiendaFk(tienda);
            Call<Usuario> request = usuarioService.registrarUsuarioTienda(usuario);
            request.enqueue(new Callback<Usuario>() {
                @Override
                public void onResponse(Call<Usuario> call, Response<Usuario> response) {
                    if (response.isSuccessful() && response.body() != null) {
                        flipper.setDisplayedChild(0);
                        Toast.makeText(MainActivity.this, R.string.registroOK, Toast.LENGTH_LONG).show();
                        actionBar.setDisplayHomeAsUpEnabled(false);
                    } else {
                        Toast.makeText(MainActivity.this, R.string.errorRegistro, Toast.LENGTH_LONG).show();
                    }
                }

                @Override
                public void onFailure(Call<Usuario> call, Throwable t) {
                    Toast.makeText(MainActivity.this, R.string.errorRegistro, Toast.LENGTH_LONG).show();
                }
            });
        }
    }

    @Override
    protected void onRestart() {
        super.onRestart();
        if (!preferences.isSesionIniciada()) {
            EditText etNombre = findViewById(R.id.etUsuario);
            EditText etPassword = findViewById(R.id.etPasswordLogin);
            etNombre.setText("");
            etPassword.setText("");
            flipper.setDisplayedChild(0);
            invalidateOptionsMenu();
        }
    }
}
