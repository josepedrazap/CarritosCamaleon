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
  Route::get('carritos/utilidad_costos/aprobar_3/{id}',  'Utilidad_costosController@aprobar_3');

  Route::get('carritos/clientes/ver_eventos/{id}',  'ClientesController@ver_eventos');

  Route::get('carritos/ingresos/mostrar_ingresos', 'IngresosController@mostrar_ingresos');
  Route::get('carritos/ingresos/show_2/{id}', 'IngresosController@show_2');

  Route::get('carritos/utilidad_costos/eventos_aprobados',  'Utilidad_costosController@index_2');
  Route::get('carritos/utilidad_costos/ingresos',  'Utilidad_costosController@ingresos');

  Route::get('carritos/ingredientes/cambiar_precio', 'IngredientesController@cambiar_precio');
  Route::get('carritos/trabajadores/editar', 'TrabajadoresController@editar');
  Route::get('carritos/clientes/editar', 'ClientesController@editar');
  Route::get('carritos/proveedores/editar', 'ProveedoresController@editar');


  Route::get('carritos/libros_contables/libro_diario', 'LibrosContablesController@LibroDiario');
  Route::get('carritos/libros_contables/libro_mayor', 'LibrosContablesController@LibroMayor');
  Route::get('carritos/libros_contables/libro_compras', 'LibrosContablesController@LibroCompras');
  Route::get('carritos/libros_contables/libro_ventas', 'LibrosContablesController@LibroVentas');

  Route::get('carritos/libros_contables', 'LibrosContablesController@index');

  Route::get('registrar', 'AuxController@registrar');
  Route::get('cambiar_contraseña', 'ResetpassController@cambiar_contraseña');

  //RUTAS DE PDF
  Route::get('carritos/pdf/despacho_checklist','PDFController@despacho_checklist');
  Route::get('carritos/pdf/balance/{date_1}/{date_2}','PDFController@balance_pdf');
  Route::get('carritos/pdf/balance_8_cols/{date_1}/{date_2}','PDFController@balance_8_cols');

  //RUTAS EXCEL
  Route::get('carritos/excel/balance_excel/{date_1}/{date_2}','Cuentas_contablesController@balance_excel');
  Route::get('carritos/excel/index_excel/{date_1}/{date_2}','HonorariosController@index_excel');
  Route::get('carritos/excel/index_excel_ingresos/{date_1}/{date_2}','IngresosController@index_excel');
  Route::get('carritos/excel/index_excel_compras/{date_1}/{date_2}','ComprasController@index_excel');
  Route::get('carritos/excel/index_excel_gastos/{date_1}/{date_2}','GastosController@index_excel');

  Route::get('/carritos/ajustes/editar', 'AjustesController@editar');
  Route::get('/carritos/compras/editar', 'ComprasController@editar');
  Route::get('/carritos/ingresos/crear', 'IngresosController@crear');
  Route::get('/carritos/ingresos/guardar', 'IngresosController@guardar');

  Route::get('carritos/eventos/cotizacion', 'EventosController@cotizacion');
  Route::get('carritos/gastos/resumen', 'GastosController@resumen');
  Route::get('carritos/gastos/pagador_empresa/{id}', 'GastosController@pagador_empresa');
  Route::get('carritos/pagos/actualizar_pago/{id_p}/{id_t}',  'PagosController@actualizar_pago');
  Route::get('carritos/ingredientes/update_precio/{bruto}/{liquido}/{iva}', 'IngredientesController@update_precio');
  //Route::get('carritos/compras/ver/{id}', 'ComprasController@ver');
  Route::get('carritos/cuentas_contables/balance', 'Cuentas_contablesController@balance');
  Route::resource('carritos/honorarios', 'HonorariosController');
  Route::resource('carritos/reset', 'ResetpassController');
  Route::resource('carritos/extras', 'ExtrasController');
  Route::resource('carritos/compras', 'ComprasController');
  Route::resource('carritos/ingredientes', 'IngredientesController');
  Route::resource('carritos/clientes', 'ClientesController');
  Route::resource('carritos/proveedores', 'ProveedoresController');
  Route::resource('carritos/pagos', 'PagosController');
  Route::resource('carritos/trabajadores', 'TrabajadoresController');
  Route::resource('carritos/productos', 'ProductosController');
  //Route::resource('carritos/eventos', 'EventosController');
  Route::resource('carritos/inventario', 'InventarioController');
  Route::resource('carritos/despacho', 'DespachoController');
  Route::resource('carritos/cotizaciones', 'CotizacionesController');

  Route::resource('carritos/gastos', 'GastosController');

  Route::resource('carritos/mercaderiaproxeventos', 'MercaderiaProxEventosController');
  Route::resource('registro', 'AuxController');
  Route::resource('carritos/utilidad_costos', 'Utilidad_costosController');
  Route::resource('carritos/cuentas_contables', 'Cuentas_contablesController');
  Route::resource('carritos/ingresos', 'IngresosController');
  Route::resource('carritos/ajustes', 'AjustesController');

  Route::get('/axios/obtener_numero_comprobante', 'AjustesController@axios_onc');
  Route::get('/axios/prueba_numero_comprobante', 'AjustesController@axios_pnc');
  Route::get('/axios/fecha', 'CotizacionesController@axios_fecha');
  Route::get('/axios/id', 'CotizacionesController@axios_id');

  Auth::routes();

  Route::get('/costo_bruto_ingredientes_producto', 'simulacionesController@costo_bruto_ingredientes_producto');
  Route::get('/calendario_eventos', 'calendarioController@index_eventos');
  Route::get('/calendario_cotizaciones', 'calendarioController@index_cotizaciones');
  Route::get('/calendario_financiero', 'calendarioController@index_financiero');

  Route::resource('/carritos/simulaciones', 'simulacionesController');

  Route::get('/home', 'calendarioController@index_eventos')->name('home');

  Route::get("/simulacion_resumen", "simulacionesController@simulador_resumen");
  Route::get('/conv_sim_a_evento', 'simulacionesController@conv_sim_a_evento');
  Route::get('/index_eventos', 'simulacionesController@index_eventos');
  Route::get("/ver_evento", "simulacionesController@ver_evento");
  Route::get('/editar_simulacion', "simulacionesController@editar_simulacion");
  Route::get('/simulador_store', 'simulacionesController@simulador_store');
  Route::get('/simulador_edit_store', 'simulacionesController@simulador_edit_store');
  Route::get('/eliminar_simulacion', 'simulacionesController@eliminar_simulacion');
  Route::get('/eliminar_evento', 'simulacionesController@eliminar_evento');
  Route::get("/editar_evento", 'simulacionesController@editar_evento');

  Route::get('/estado_evento', 'simulacionesController@estado_evento');
  Route::get('/resumen_evento_calendario', 'simulacionesController@resumen_evento_calendario');
  Route::get('/realizar_cancelar', 'simulacionesController@realizar_cancelar');
  Route::get('/eventos', 'calendarioController@eventos');
  Route::get('/simulador', 'simulacionesController@simulador');
  Route::get('/calendario', 'calendarioController@calendario');
  Route::get('/carritos/cotizaciones_text', 'CotizacionesController@cotizaciones_text');
  Route::get('/carritos/cotizaciones_text_2', 'CotizacionesController@cotizaciones_text_2');

  Route::get('/carritos/cotizaciones_guardar', 'CotizacionesController@cotizaciones_guardar');
  Route::get('/carritos/cotizaciones_actualizar', 'CotizacionesController@cotizaciones_actualizar');
