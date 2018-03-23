<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

  Route::get('carritos/extras/vehiculos',  'AuxController@vehiculos');
  Route::get('carritos/extras/eliminar_vehiculo/{id}',  'AuxController@eliminar_vehiculo');
  Route::view('carritos/extras/calendario', 'carritos.extras.calendario ');
  Route::get('carritos/pagos',  'PagosController@store');
  Route::get('carritos/pagos/pagar/{id}',  'PagosController@pagar');
  Route::get('carritos/pagos/vertodos/{id}',  'PagosController@vertodos');
  Route::get('carritos/utilidad_costos/aprobar/{id}',  'Utilidad_costosController@aprobar');
  Route::get('carritos/utilidad_costos/aprobar_2/{id}',  'Utilidad_costosController@aprobar_2');
  Route::get('carritos/ingresos/mostrar_ingresos', 'IngresosController@mostrar_ingresos');
  Route::get('carritos/ingresos/show_2/{id}', 'IngresosController@show_2');

  Route::get('carritos/utilidad_costos/eventos_aprobados',  'Utilidad_costosController@index_2');
  Route::get('carritos/utilidad_costos/ingresos',  'Utilidad_costosController@ingresos');

  Route::get('carritos/ingredientes/cambiar_precio', 'IngredientesController@cambiar_precio');

  Route::get('registrar', 'AuxController@registrar');
  Route::get('cambiar_contraseña', 'AuxController@cambiar_contraseña');

  //RUTAS DE PDF


  Route::get('carritos/pdf/despacho_checklist/{id}','PDFController@despacho_checklist');
  Route::get('carritos/pdf/balance/{date_1}/{date_2}','PDFController@balance_pdf');
  Route::get('carritos/pdf/balance_8_cols/{date_1}/{date_2}','PDFController@balance_8_cols');

  Route::get('carritos/eventos/cotizacion', 'EventosController@cotizacion');
  Route::get('carritos/gastos/resumen', 'GastosController@resumen');
  Route::get('carritos/gastos/pagador_empresa/{id}', 'GastosController@pagador_empresa');
  Route::get('carritos/pagos/actualizar_pago/{id_p}/{id_t}',  'PagosController@actualizar_pago');
  Route::get('carritos/ingredientes/update_precio/{bruto}/{liquido}/{iva}', 'IngredientesController@update_precio');
  //Route::get('carritos/compras/ver/{id}', 'ComprasController@ver');
  Route::get('carritos/cuentas_contables/balance', 'Cuentas_contablesController@balance');

  Route::resource('carritos/compras', 'ComprasController');
  Route::resource('carritos/ingredientes', 'IngredientesController');
  Route::resource('carritos/clientes', 'ClientesController');
  Route::resource('carritos/proveedores', 'ProveedoresController');
  Route::resource('carritos/pagos', 'PagosController');
  Route::resource('carritos/trabajadores', 'TrabajadoresController');
  Route::resource('carritos/productos', 'ProductosController');
  Route::resource('carritos/eventos', 'EventosController');
  Route::resource('carritos/inventario', 'InventarioController');
  Route::resource('carritos/despacho', 'DespachoController');
  Route::resource('carritos/cotizaciones', 'CotizacionesController');
  Route::resource('carritos/gastos', 'GastosController');
  Route::resource('carritos/mercaderiaproxeventos', 'MercaderiaProxEventosController');
  Route::resource('registro', 'AuxController');
  Route::resource('carritos/utilidad_costos', 'Utilidad_costosController');
  Route::resource('carritos/cuentas_contables', 'Cuentas_contablesController');
  Route::resource('carritos/ingresos', 'IngresosController');


  Auth::routes();

  Route::get('/home', 'EventosController@index')->name('home');
