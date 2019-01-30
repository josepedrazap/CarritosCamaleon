<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\Simulaciones;
use CamaleonERP\Simulacion_productos;
use CamaleonERP\Simulacion_extras;
use CamaleonERP\Simulacion_ingr_extra;
use CamaleonERP\Simulacion_nuevo;
use CamaleonERP\Simulacion_valores;
use CamaleonERP\Simulacion_trabajadores;
use CamaleonERP\Eventos_2;
use CamaleonERP\Eventos_tienen_trabajadores;
use CamaleonERP\Inventario;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\UserFormRequest;
use DB;


class simulacionesController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(){
    $simulaciones = DB::table('simulaciones')
    ->where('simulaciones.estado', '=', 1)
    ->orderBy('id','asc')
    ->get();
    return View('carritos.simulaciones.index', ["simulaciones"=>$simulaciones]);
  }
  public function simulador(Request $request){

    $fecha = $request->get("fecha");
    $productos = DB::table('productos')
    ->where('productos.condicion', '=', 1)
    ->get();
    $extras = DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'extras')
    ->where('sv.condicion', '=', 1)
    ->get();
    $ingr_extras=DB::table('ingredientes')->get();

    $tipo_instruccion = $request->get("tipo_instruccion");

    return view('carritos.simulaciones.simulador', ["productos" => $productos, "tipo_instruccion" => $tipo_instruccion,"fecha"=>$fecha, "ingredientes" => $ingr_extras, "extras" => $extras]);
  }
  public function costo_bruto_ingredientes_producto(Request $request){
    $id = $request->get('id');
    $cantidad = $request->get('cantidad');

    $ingredientes = DB::table('productos_tienen_ingredientes as pti')
    ->where('id_producto', '=', $id)
    ->join('ingredientes as ingr','pti.id_ingrediente', '=', 'ingr.id')
    ->select('ingr.nombre', 'pti.unidad', 'ingr.precio_bruto', 'pti.porcion')
    ->groupBy('nombre', 'pti.unidad', 'ingr.precio_bruto', 'pti.porcion')
    ->get();
    $total_acumulado = 0;

    for($i = 0; $i < count($ingredientes); $i++){
      $total = $ingredientes[$i]->porcion * $cantidad;
      if($ingredientes[$i]->unidad == 'gramos'){
        $total = $total / 1000; //pasa a kilos
        $total = $total * $ingredientes[$i]->precio_bruto;
      }else{
        $total = $total * $ingredientes[$i]->precio_bruto;
      }
      $total_acumulado += $total;
    }

    return $total_acumulado;
  }
  public function editar(Request $request){
    $id = $request->get('id');
    // $simulacion = DB::table('simulaciones as sim')
    // ->where('sim.id', '=', $id)
    // ->join('Simulacion_valores as sim_val', )
  }
  public function simulador_store(Request $request){
    DB::beginTransaction();
    try{

      $simulacion = new Simulaciones;
      $simulacion->nombre = $request->get('nombre_simulacion');
      $simulacion->fecha = $request->get('fecha_simulacion');
      $simulacion->descripcion = $request->get('descripcion');
      $simulacion->estado = 1;
      $simulacion->save();

      $simulacion_datos = new Simulacion_valores;
      $simulacion_datos->costo_parcial_neto = $request->get('costo_parcial_neto');
      $simulacion_datos->costo_parcial_bruto = $request->get('costo_parcial_bruto');
      $simulacion_datos->ganancia_neta = $request->get('ganancia_neta');
      $simulacion_datos->ganancia_bruta = $request->get('ganancia_bruta');
      $simulacion_datos->porcentaje_ganacia = $request->get('porcentaje_ganacia');
      $simulacion_datos->total_final_neto = $request->get('total_final_neto');
      $simulacion_datos->total_final_iva = $request->get('total_final_iva');
      $simulacion_datos->total_final_bruto = $request->get('total_final_bruto');
      $simulacion_datos->id_simulacion = $simulacion->id;
      $simulacion_datos->save();

      if($request->get('id_producto')){
        $i = 0;
        $productos = $request->get('id_producto');
        $cantidad_p = $request->get('cant_prods_');
        $precio_neto_unidad = $request->get('precio_neto_unidad_producto');
        $costo_neto = $request->get('costo_neto_unidad_producto_');
        while($i < count($productos)){
                  $sim_prod = new Simulacion_productos;
                  $sim_prod->id_simulacion = $simulacion->id;
                  $sim_prod->id_producto = $productos[$i];
                  $sim_prod->cantidad = $cantidad_p[$i];
                  $sim_prod->precio_neto_unidad = $precio_neto_unidad[$i];
                  $sim_prod->costo_neto = $costo_neto[$i];
                  $sim_prod->save();
                  $i++;
        }
      }
      if($request->get('id_extra')){
            $i = 0;
            $extras = $request->get('id_extra');
            $precio_extra = $request->get('precio_neto_unidad_extra');
            $costo_extra = $request->get('costo_neto_extra_');
            $cantidad = $request->get("cant_extras_");
            while($i < count($extras)){
                $sim_extras = new Simulacion_extras;
                $sim_extras->id_simulacion = $simulacion->id;
                $sim_extras->id_extra = $extras[$i];
                $sim_extras->precio_neto_unidad = $precio_extra[$i];
                $sim_extras->costo_neto_unidad = $costo_extra[$i];
                $sim_extras->cantidad = $cantidad[$i];
                $sim_extras->save();
                $i++;
            }
        }
      if($request->get('nuevo_nombre')){
            $i = 0;
            $nuevo = $request->get('nuevo_nombre');
            $cant_nuevo = $request->get('cant_nuevos_');
            $costo_nuevo = $request->get('costo_neto_nuevo');
            $precio_nuevo = $request->get('precio_neto_unidad_nuevo');
            while($i < count($nuevo)){
                $sim_nuevo = new Simulacion_nuevo;
                $sim_nuevo->id_simulacion = $simulacion->id;
                $sim_nuevo->nombre = $nuevo[$i];
                $sim_nuevo->cantidad = $cant_nuevo[$i];
                $sim_nuevo->precio_neto_unidad = $precio_nuevo[$i];
                $sim_nuevo->costo_neto_unidad = $costo_nuevo[$i];
                $sim_nuevo->save();
                $i++;
            }
      }
      if($request->get('id_ingr')){
              $i = 0;
              $extras_ingr = $request->get('id_ingr');
              $cant_extra_ingr = $request->get('cant_ingrs_');
              $costo_ingr_ext = $request->get('costo_total_neto_ingr');
              $precio_ingr_ext = $request->get('precio_total_neto_ingr');
              $porcion = $request->get("porcion");
              while($i < count($extras_ingr)){
                  $sim_ingr_extra = new Simulacion_ingr_extra;
                  $sim_ingr_extra->id_simulacion = $simulacion->id;
                  $sim_ingr_extra->id_ingrediente = $extras_ingr[$i];
                  $sim_ingr_extra->cantidad = $cant_extra_ingr[$i];
                  $sim_ingr_extra->porcion_unitaria = $porcion[$i];
                  $sim_ingr_extra->precio_neto_unidad = $precio_ingr_ext[$i];
                  $sim_ingr_extra->costo_neto_unidad = $costo_ingr_ext[$i];
                  $sim_ingr_extra->save();
                  $i++;
              }
      }
      if($request->get('id_trabajadores_2')){
        $i = 0;
        $trabajadores_2 = $request->get('id_trabajadores_2');
        $sueldo = $request->get('honorario_trabajadores_2_');
        $cantidad = $request->get('cant_trabajadores_2_');

        while($i < count($trabajadores_2)){
            $trabajador = new Simulacion_trabajadores;
            $trabajador->nombre = $trabajadores_2[$i];
            $trabajador->sueldo = $sueldo[$i];
            $trabajador->cantidad = $cantidad[$i];
            $trabajador->id_simulacion = $simulacion->id;
            $trabajador->save();
            $i++;
        }
      }

      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    if($request->get("tipo_instruccion") == "2"){
      return Redirect::to("/simulacion_resumen?id=" . $simulacion->id);
    }else if($request->get("tipo_instruccion") == "3"){
      return Redirect::to("/calendario");
    }
    return Redirect::to("/carritos/simulaciones");
  }
  public function simulador_edit_store(Request $request){
    DB::beginTransaction();

    try{
      if($request->get("tipo_instruccion") == 4){ //En caso de editar evento
        $id_evento = $request->get("id_evento");
        $id_temp = $request->get("id_temp");

      }else{
        $id_temp = $request->get("id_temp");
        $deletedRows = Simulaciones::where('id', $id_temp)->delete();
        $deletedRows = Simulacion_valores::where('id_simulacion', $id_temp)->delete();
        $deletedRows = Simulacion_productos::where('id_simulacion', $id_temp)->delete();
        $deletedRows = Simulacion_extras::where('id_simulacion', $id_temp)->delete();
        $deletedRows = Simulacion_ingr_extra::where('id_simulacion', $id_temp)->delete();
        $deletedRows = simulacion_nuevo::where('id_simulacion', $id_temp)->delete();
        $deletedRows = Simulacion_trabajadores::where('id_simulacion', $id_temp)->delete();
      }

      $simulacion = new Simulaciones;
      $simulacion->nombre = $request->get('nombre_simulacion');
      $simulacion->fecha = $request->get('fecha_simulacion');
      $simulacion->descripcion = $request->get('descripcion');
      $simulacion->estado = 1;
      $simulacion->save();

      $simulacion_datos = new Simulacion_valores;
      $simulacion_datos->costo_parcial_neto = $request->get('costo_parcial_neto');
      $simulacion_datos->costo_parcial_bruto = $request->get('costo_parcial_bruto');
      $simulacion_datos->ganancia_neta = $request->get('ganancia_neta');
      $simulacion_datos->ganancia_bruta = $request->get('ganancia_bruta');
      $simulacion_datos->porcentaje_ganacia = $request->get('porcentaje_ganacia');
      $simulacion_datos->total_final_neto = $request->get('total_final_neto');
      $simulacion_datos->total_final_iva = $request->get('total_final_iva');
      $simulacion_datos->total_final_bruto = $request->get('total_final_bruto');
      $simulacion_datos->id_simulacion = $simulacion->id;
      $simulacion_datos->save();

      if($request->get('id_producto')){
        $i = 0;
        $productos = $request->get('id_producto');
        $cantidad_p = $request->get('cant_prods_');
        $precio_neto_unidad = $request->get('precio_neto_unidad_producto');
        $costo_neto = $request->get('costo_neto_unidad_producto_');
        while($i < count($productos)){
                  $sim_prod = new Simulacion_productos;
                  $sim_prod->id_simulacion = $simulacion->id;
                  $sim_prod->id_producto = $productos[$i];
                  $sim_prod->cantidad = $cantidad_p[$i];
                  $sim_prod->precio_neto_unidad = $precio_neto_unidad[$i];
                  $sim_prod->costo_neto = $costo_neto[$i];
                  $sim_prod->save();
                  $i++;
        }
      }
      if($request->get('id_extra')){
            $i = 0;
            $extras = $request->get('id_extra');
            $precio_extra = $request->get('precio_neto_unidad_extra');
            $costo_extra = $request->get('costo_neto_extra_');
            $cantidad = $request->get("cant_extras_");
            while($i < count($extras)){
                $sim_extras = new Simulacion_extras;
                $sim_extras->id_simulacion = $simulacion->id;
                $sim_extras->id_extra = $extras[$i];
                $sim_extras->precio_neto_unidad = $precio_extra[$i];
                $sim_extras->costo_neto_unidad = $costo_extra[$i];
                $sim_extras->cantidad = $cantidad[$i];
                $sim_extras->save();
                $i++;
            }
        }
      if($request->get('nuevo_nombre')){
            $i = 0;
            $nuevo = $request->get('nuevo_nombre');
            $cant_nuevo = $request->get('cant_nuevos_');
            $costo_nuevo = $request->get('costo_neto_nuevo');
            $precio_nuevo = $request->get('precio_neto_unidad_nuevo');
            while($i < count($nuevo)){
                $sim_nuevo = new Simulacion_nuevo;
                $sim_nuevo->id_simulacion = $simulacion->id;
                $sim_nuevo->nombre = $nuevo[$i];
                $sim_nuevo->cantidad = $cant_nuevo[$i];
                $sim_nuevo->precio_neto_unidad = $precio_nuevo[$i];
                $sim_nuevo->costo_neto_unidad = $costo_nuevo[$i];
                $sim_nuevo->save();
                $i++;
            }
      }
      if($request->get('id_ingr')){
              $i = 0;
              $extras_ingr = $request->get('id_ingr');
              $cant_extra_ingr = $request->get('cant_ingrs_');
              $costo_ingr_ext = $request->get('costo_total_neto_ingr');
              $precio_ingr_ext = $request->get('precio_total_neto_ingr');
              $porcion = $request->get("porcion");
              while($i < count($extras_ingr)){
                  $sim_ingr_extra = new Simulacion_ingr_extra;
                  $sim_ingr_extra->id_simulacion = $simulacion->id;
                  $sim_ingr_extra->id_ingrediente = $extras_ingr[$i];
                  $sim_ingr_extra->cantidad = $cant_extra_ingr[$i];
                  $sim_ingr_extra->porcion_unitaria = $porcion[$i];
                  $sim_ingr_extra->precio_neto_unidad = $precio_ingr_ext[$i];
                  $sim_ingr_extra->costo_neto_unidad = $costo_ingr_ext[$i];
                  $sim_ingr_extra->save();
                  $i++;
              }
      }
      if($request->get('id_trabajadores_2')){
        $i = 0;
        $trabajadores_2 = $request->get('id_trabajadores_2');
        $sueldo = $request->get('honorario_trabajadores_2_');
        $cantidad = $request->get('cant_trabajadores_2_');

        while($i < count($trabajadores_2)){
            $trabajador = new Simulacion_trabajadores;
            $trabajador->nombre = $trabajadores_2[$i];
            $trabajador->sueldo = $sueldo[$i];
            $trabajador->cantidad = $cantidad[$i];
            $trabajador->id_simulacion = $simulacion->id;
            $trabajador->save();
            $i++;
        }
      }

      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    if($request->get("tipo_instruccion") == "2"){
      return Redirect::to("/simulacion_resumen?id=" . $simulacion->id);
    }else if($request->get("tipo_instruccion") == "3"){
      return Redirect::to("/calendario");
    }else if($request->get("tipo_instruccion") == "4"){
      return Redirect::to("/simulacion_resumen?id=" . $simulacion->id . "&id_evento=" . $id_evento . "&tipo_instruccion=4" . "id_sim=" . $id_temp);

    }
    return Redirect::to("/carritos/simulaciones");
  }
  public function create(Request $request){

    $id = $request->id;

    $simulacion = DB::table('simulaciones')
    ->where('simulaciones.id', '=', $id)
    ->get();

    $ingr_extras=DB::table('ingredientes as ingr')
    ->join('simulacion_ingr_extra as etie', 'etie.id_extra', '=', 'ingr.id')
    ->where('etie.id_simulacion', '=', $id)
    ->select('ingr.nombre', 'etie.cantidad', 'etie.id', 'ingr.porcion_',  'ingr.precio_bruto', 'ingr.uni_porcion')
    ->get();

    $num_ingr_ext=DB::table('ingredientes as ingr')
    ->join('simulacion_ingr_extra as etie', 'etie.id_extra', '=', 'ingr.id')
    ->where('etie.id_simulacion', '=', $id)
    ->count();

    $productos=DB::table('productos as prod')
    ->join('simulacion_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_simulacion', '=', $id)
    ->select('prod.nombre', 'etp.cantidad as cantidad', 'etp.id as id_etp', 'precio', 'base', DB::raw('sum(cantidad) as sum'))
    ->groupBy('nombre','cantidad', 'precio', 'base',  'id_etp')
    ->get();

    $base=DB::table('productos as prod')
    ->join('simulacion_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_simulacion', '=', $id)
    ->select('base', DB::raw('sum(cantidad) as sum'))
    ->groupBy('base')
    ->get();

    $total=DB::table('productos as prod')
    ->join('simulacion_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_simulacion', '=', $id)
    ->select( 'etp.id_simulacion as id_e', DB::raw('sum(cantidad*precio) as sum'))
    ->groupBy('id_e')
    ->get();

    $extras=DB::table('selects_valores as sv')
    ->join('simulacion_extras as ete', 'sv.id', '=', 'ete.id_extra')
    ->where('ete.id_simulacion', '=', $id)
    ->get();

    $trabajadores=DB::table('trabajadores as tra')
    ->join('trabajador_detalle as td', 'tra.id', '=', 'td.id_trabajador')
    ->where('tra.condicion', '=', 1)
    ->select('tra.nombre as nombre', 'td.maneja', 'tra.id as id', 'tra.apellido as apellido')
    ->groupBy('nombre', 'maneja', 'id', 'apellido')
    ->get();


    $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('simulacion_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_simulacion', '=', $id)
      //->where('is.id_evento', '=', $id)
      ->select('ingr.nombre as nombre', 'precio_bruto', 'pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre',  'unidad', 'precio_bruto', 'id_ingr', 'stock', 'uni_inv')
      ->get();

      $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('simulacion_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_simulacion', '=', $id)
      ->select('ingr.nombre as nombre', 'ingr.inventareable' ,'precio_bruto','pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre', 'ingr.inventareable' ,'unidad', 'precio_bruto','id_ingr', 'stock', 'uni_inv')
      ->get();

      $num_prod=DB::table('simulacion_productos as etp')
      ->where('etp.id_simulacion', '=', $id)
      ->count('id_producto');

      $num_ext=DB::table('simulacion_extras as ete')
      ->where('ete.id_simulacion', '=', $id)
      ->count('id_extra');

      $num_ingr_ext=DB::table('ingredientes as ingr')
      ->join('simulacion_ingr_extra as etie', 'etie.id_extra', '=', 'ingr.id')
      ->where('etie.id_simulacion', '=', $id)
      ->count();

      $nuevos_items=DB::table('simulacion_nuevo as sn')
      ->where('sn.id_simulacion', '=', $id)
      ->get();

    $i_ingr = count($ingredientes);
    return view('carritos.simulaciones.create', ["simulacion"=>$simulacion, "ingr_extras"=>$ingr_extras,
                                            "ingredientes"=>$ingredientes, "num_prod" => $num_prod,
                                             "productos"=>$productos, "extras"=>$extras,
                                                "base" => $base, "num_ext"=>$num_ext, "num_ingr_ext"=>$num_ingr_ext,
                                             "total"=>$total, "trabajadores" => $trabajadores,
                                             "pago_cocinero"=>20000, "i_ingr" => $i_ingr, "nuevos_items" => $nuevos_items
                                             ]);
  }
  public function simulador_resumen(Request $request){

    if($request->get("tipo_instruccion") == 4){ //En caso de editar evento
          $id_evento = $request->get("id_evento");
          $trabajadores_antiguos = DB::table('eventos_tienen_trabajadores as ett')
          ->where('ett.id_evento', '=', $id_evento)
          ->join('trabajadores as tra', 'tra.id', '=', 'ett.id_trabajador')
          ->get();
          $evento = DB::table('eventos_2 as eve')
          ->where('eve.id', '=',   $id_evento)
          ->get();
          $id_sim_antigua = $request->get("id_sim");
    }
    $id_sim = $request->get("id");

    $clientes = DB::table("clientes")->get();
    $trabajadores_lista = DB::table('trabajadores as tra')
    ->join('trabajador_detalle as td', 'tra.id', '=', 'td.id_trabajador')
    ->where('tra.condicion', '=', 1)
    ->select('tra.nombre as nombre', 'td.maneja', 'tra.id as id', 'tra.apellido as apellido')
    ->groupBy('nombre', 'maneja', 'id', 'apellido')
    ->get();

    $trabajadores = DB::table("simulacion_trabajadores")
    ->where("id_simulacion", "=", $id_sim)->get();
    $productos = DB::table("simulacion_productos as sp")
    ->join("productos as p", "p.id", "=", "sp.id_producto")
    ->where("id_simulacion", "=", $id_sim)
    ->get();
    $nuevos = DB::table("simulacion_nuevo")->where("id_simulacion", "=", $id_sim)->get();
    $extras = DB::table("simulacion_extras as se")
    ->join("selects_valores as p", "p.id", "=", "se.id_extra")
    ->where("id_simulacion", "=", $id_sim)
    ->get();
    $simulacion = DB::table("simulacion_valores")->where("id_simulacion", "=", $id_sim)->get();
    $sim = DB::table("simulaciones")->where("simulaciones.id", "=", $id_sim)->get();
    $ingredientes_extra = DB::table("simulacion_ingr_extra")
    ->where("simulacion_ingr_extra.id_simulacion", "=", $id_sim)
    ->join("ingredientes","ingredientes.id", "=", "simulacion_ingr_extra.id_ingrediente")
    ->get();

    if($request->get("tipo_instruccion") == 4){
      return view('carritos.simulaciones.crear_evento_edit', ["productos" => $productos,
                                                          "trabajadores" => $trabajadores,
                                                          "nuevos" => $nuevos, "evento" => $evento,
                                                          "extras" => $extras, "simulacion" => $simulacion,
                                                          "trabajadores_antiguos" => $trabajadores_antiguos,
                                                          "clientes" => $clientes,"id_evento" => $id_evento,
                                                          "trabajadores_lista" => $trabajadores_lista,
                                                          "id" => $id_sim, "sim" => $sim,
                                                          "id_sim_antigua" => $id_sim_antigua,
                                                          "ingredientes_extras" => $ingredientes_extra]);

    }else{
      return view('carritos.simulaciones.crear_evento', ["productos" => $productos,
                                                          "trabajadores" => $trabajadores,
                                                          "nuevos" => $nuevos,
                                                          "extras" => $extras,
                                                          "simulacion" => $simulacion,
                                                          "clientes" => $clientes,
                                                          "trabajadores_lista" => $trabajadores_lista,
                                                          "id" => $id_sim, "sim" => $sim,
                                                          "ingredientes_extras" => $ingredientes_extra]);
    }
  }
  public function conv_sim_a_evento(Request $request){
    DB::beginTransaction();

    if($request->get("id_evento")){ //si es evento!!!
      $d = Eventos_2::where("id", $request->get("id_evento"))->delete();
      $d = Eventos_tienen_trabajadores::where("id_evento", $request->get("id_evento"))->delete();
      $id_temp = $request->get("id_sim_antigua");
      $deletedRows = Simulaciones::where('id', $id_temp)->delete();
      $deletedRows = Simulacion_valores::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_productos::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_extras::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_ingr_extra::where('id_simulacion', $id_temp)->delete();
      $deletedRows = simulacion_nuevo::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_trabajadores::where('id_simulacion', $id_temp)->delete();
    }

    try{
      $cliente_ = DB::table('clientes')->where("clientes.id", "=", $request->get("cliente"))->get();
      $evento = new Eventos_2;
      $evento->id_simulacion = $request->get("id");
      $evento->id_cliente = $request->get("cliente");
      $evento->fecha_hora = $request->get("fecha_hora");
      $evento->direccion = $request->get("direccion");
      $evento->condicion = 1;
      $evento->estado_pago = 0;
      $evento->cliente = $cliente_[0]->nombre . " " . $cliente_[0]->apellido;
      $evento->descripcion = $request->get("descripcion");
      $evento->save();

      $simulacion = Simulaciones::findOrFail($request->get("id"));
      $simulacion->estado = 2;
      $simulacion->update();

      $trabajadores = $request->get("trabajadores");
      $monto = $request->get("monto");
      $trabajo = $request->get("trabajo");
      $cont = 0;

      while($cont < count($trabajadores)){
        $trab_temp = new Eventos_tienen_trabajadores;
        $trab_temp->id_evento = $evento->id;
        $trab_temp->id_trabajador = $trabajadores[$cont];
        $trab_temp->estado = 1;
        $trab_temp->trabajo = $trabajo[$cont];
        $trab_temp->monto = $monto[$cont];
        $trab_temp->save();
        $cont++;
      }
      DB::commit();
      return Redirect::to("/ver_evento?id=" . $evento->id);
    }catch(Exception $e){
      DB::rollback();
    }
  }
  public function index_eventos(Request $request){
    $eventos = DB::table('eventos_2')
    //->join("clientes as cli", "cli.id", "=", "eventos_2.id_cliente")
    ->orderBy('eventos_2.id','desc')
    ->paginate(7);
    return View('carritos.simulaciones.index_eventos', ["eventos"=>$eventos]);
  }
  public function ver_evento(Request $request){
    $id = $request->get("id");

    $evento = DB::table("eventos_2")
    ->where("eventos_2.id", "=", $id)
    ->join("clientes", "clientes.id", "=", "eventos_2.id_cliente")
    ->get();

    $id_sim = $evento[0]->id_simulacion;

    $simulacion = DB::table("simulacion_valores")
    ->where("id_simulacion", "=", $id_sim)->get();

    $productos = DB::table("simulacion_productos as sp")
    ->join("productos as p", "p.id", "=", "sp.id_producto")
    ->where("id_simulacion", "=", $id_sim)
    ->get();
    $nuevos = DB::table("simulacion_nuevo")
    ->where("id_simulacion", "=", $id_sim)->get();
    $extras = DB::table("simulacion_extras as se")
    ->join("selects_valores as p", "p.id", "=", "se.id_extra")
    ->where("id_simulacion", "=", $id_sim)
    ->get();

    $trabajadores = DB::table("eventos_tienen_trabajadores as ett")
    ->where("ett.id_evento", "=", $id)
    ->join("trabajadores", "trabajadores.id", "=", "ett.id_trabajador")
    ->get();
    $otros = DB::table("simulacion_nuevo")->where("id_simulacion", "=", $id_sim)->get();

    $ingredientes=DB::table('productos as prod')
    ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
    ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
    ->join('simulacion_productos as sp', 'prod.id', '=', 'sp.id_producto')
    ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
    ->where('sp.id_simulacion', '=', $id_sim)
    ->select('ingr.nombre as nombre', 'ingr.inventareable' ,'precio_bruto','pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*sp.cantidad) as sum'))
    ->groupBy('nombre', 'ingr.inventareable' ,'unidad', 'precio_bruto','id_ingr', 'stock', 'uni_inv')
    ->get();

    $ingredientes_extras=DB::table("simulacion_ingr_extra as sie")
    ->join('ingredientes as ingr', 'sie.id_ingrediente', '=', 'ingr.id')
    ->where("sie.id_simulacion", "=", $id_sim)
    ->get();

    for($i = 0; $i < count($ingredientes); $i++){

      for($j = 0; $j < count($ingredientes_extras); $j++){
        if($ingredientes[$i]->id_ingr == $ingredientes_extras[$j]->id_ingrediente){
          $aux = $ingredientes_extras[$j]->cantidad * $ingredientes_extras[$j]->porcion_unitaria;
          $ingredientes[$i]->sum += $aux;
          $ingredientes_extras[$j]->aux = 1;
        }else{
          $ingredientes_extras[$j]->aux = 0;
        }
      }
    }


    return view('carritos.simulaciones.ver_evento', ["evento"=>$evento, "simulacion" => $simulacion,
                                                    "productos" => $productos, "nuevos" =>$nuevos,
                                                    "extras" => $extras, "base" => "lala",
                                                    "trabajadores" => $trabajadores, "otros" => $otros,
                                                    "ingredientes" => $ingredientes, "id" => $id,
                                                    "ingredientes_extras" => $ingredientes_extras]);

  }
  public function realizar_cancelar(Request $request){
    $id = $request->get("id");
    $i = $request->get("i");

    $evento = Eventos_2::findOrFail($id);
    if($i){
      $evento->condicion = 2;
    }else{
      $evento->condicion = 3;
    }
    $evento->save();
    return 1;
  }
  public function estado_evento (Request $request){
    $id = $request->get("id");

    $evento = DB::table("eventos_2")
    ->where("eventos_2.id", "=", $id)
    ->get();

    return view('carritos.simulaciones.estado', ["evento" => $evento]);
  }
  public function resumen_evento_calendario (Request $request){
    $id_sim = $request->get("id");
    $trabajadores = DB::table("simulacion_trabajadores")
    ->where("id_simulacion", "=", $id_sim)->get();
    $productos = DB::table("simulacion_productos as sp")
    ->join("productos as p", "p.id", "=", "sp.id_producto")
    ->where("id_simulacion", "=", $id_sim)
    ->get();
    $nuevos = DB::table("simulacion_nuevo")->where("id_simulacion", "=", $id_sim)->get();
    $extras = DB::table("simulacion_extras as se")
    ->join("selects_valores as p", "p.id", "=", "se.id_extra")
    ->where("id_simulacion", "=", $id_sim)
    ->get();
    $simulacion = DB::table("simulacion_valores")->where("id_simulacion", "=", $id_sim)->get();
    $sim = DB::table("simulaciones")->where("simulaciones.id", "=", $id_sim)->get();
    $ingredientes_extra = DB::table("simulacion_ingr_extra")
    ->where("simulacion_ingr_extra.id_simulacion", "=", $id_sim)
    ->join("ingredientes","ingredientes.id", "=", "simulacion_ingr_extra.id_ingrediente")
    ->get();

    return view('carritos.simulaciones.resumen_calendario', ["productos" => $productos,
                                                            "trabajadores" => $trabajadores,
                                                            "nuevos" => $nuevos,
                                                            "extras" => $extras,
                                                            "simulacion" => $simulacion,
                                                            "sim" => $sim, "id"=>$id_sim,
                                                            "ingredientes_extras" => $ingredientes_extra]);
  }
  public function editar_simulacion(Request $request){
    $fecha = $request->get("fecha");
    $id = $request->get("id");

    $ev = DB::table('eventos_2 as eve')
    ->where("eve.id_simulacion", '=', $id)
    ->get();

    if(count($ev)){
      return "Ésta simulación ya se convirtió en evento por lo que no puede ser modificada. No utilice las direcciones de navegación url para manipular el programa";
    }

    $productos = DB::table('productos')
    ->where('productos.condicion', '=', 1)
    ->get();
    $extras = DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'extras')
    ->where('sv.condicion', '=', 1)
    ->get();
    $ingr_extras=DB::table('ingredientes')->get();


    $productos_sim = DB::table('simulacion_productos as sp')
    ->join('productos as p', 'p.id', '=', 'sp.id_producto')
    ->where('sp.id_simulacion', '=', $id)
    ->get();
    $trabajadores = DB::table('simulacion_trabajadores as st')
    ->where('st.id_simulacion', '=', $id)
    ->get();
    $ingredientes_sim = DB::table('simulacion_ingr_extra as sie')
    ->join('ingredientes as ingr', 'ingr.id', '=', 'sie.id_ingrediente')
    ->where('sie.id_simulacion', '=', $id)
    ->get();
    $extras_sim = DB::table('simulacion_extras as es')
    ->join('selects_valores as sv', 'sv.id', '=', 'es.id_extra')
    ->where('es.id_simulacion', '=', $id)
    ->get();
    $nuevos_sim = DB::table('simulacion_nuevo as sn')
    ->where('sn.id_simulacion', '=', $id)
    ->get();
    $simulacion = DB::table("simulaciones as s")
    ->where("s.id", '=', $id)
    ->get();

    $tipo_instruccion = $request->get("tipo_instruccion");

    return view('carritos.simulaciones.simulador_edit', ["trabajadores" => $trabajadores,
                                                          "productos_sim" => $productos_sim,
                                                          "productos" => $productos, "id_sim" => $id,
                                                          "ingredientes_sim" => $ingredientes_sim,
                                                          "tipo_instruccion" => $tipo_instruccion,
                                                          "fecha"=>$fecha, "extras_sim" => $extras_sim,
                                                          "ingredientes" => $ingr_extras,
                                                          "extras" => $extras, "nuevos_sim" => $nuevos_sim,
                                                          "simulacion" => $simulacion]);
  }
  public function eliminar_simulacion(Request $request){
    DB::beginTransaction();
    try{
      $id_temp = $request->get("id");
      $deletedRows = Simulaciones::where('id', $id_temp)->delete();
      $deletedRows = Simulacion_valores::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_productos::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_extras::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_ingr_extra::where('id_simulacion', $id_temp)->delete();
      $deletedRows = simulacion_nuevo::where('id_simulacion', $id_temp)->delete();
      $deletedRows = Simulacion_trabajadores::where('id_simulacion', $id_temp)->delete();

      DB::commit();
      return Redirect::to("/carritos/simulaciones");
    }catch(Exception $e){
      DB::rollback();
    }
  }
  public function eliminar_evento(Request $request){
    DB::beginTransaction();
    try{
      $id_temp = $request->get("id");
      $evento = DB::table("eventos_2 as eve")
      ->where('eve.id', '=', $id_temp)
      ->get();
      if(count($evento)){
        if($evento[0]->condicion == 2){
          return "El evento  " . $id_temp . " ya fue realizado por lo que no podrá ser borrado.";
        }else{
          $id_temp = $evento[0]->id_simulacion;
          $deletedRows = Eventos_tienen_trabajadores::where('id_evento', '=', $evento[0]->id)->delete();
          $deletedRows = Eventos_2::where('id', '=', $evento[0]->id)->delete();
          $deletedRows = Simulaciones::where('id', $id_temp)->delete();
          $deletedRows = Simulacion_valores::where('id_simulacion', $id_temp)->delete();
          $deletedRows = Simulacion_productos::where('id_simulacion', $id_temp)->delete();
          $deletedRows = Simulacion_extras::where('id_simulacion', $id_temp)->delete();
          $deletedRows = Simulacion_ingr_extra::where('id_simulacion', $id_temp)->delete();
          $deletedRows = simulacion_nuevo::where('id_simulacion', $id_temp)->delete();
          $deletedRows = Simulacion_trabajadores::where('id_simulacion', $id_temp)->delete();
        }
      }else{
        return "No existe el evento " . $id_temp;
      }
      DB::commit();
      return Redirect::to("/index_eventos");
    }catch(Exception $e){
      DB::rollback();
    }
  }
  public function editar_evento(Request $request){

    $id_evento = $request->get("id");

    $ev = DB::table('eventos_2 as eve')
    ->where("eve.id", '=', $id_evento)
    ->get();

    if($ev[0]->condicion != 1 ){
      return "No se pueden modificar eventos ya realizados o cancelados.";
    }

    $sim = DB::table('simulaciones as sim')
    ->where('id', '=', $ev[0]->id_simulacion)
    ->get();

    $fecha = $sim[0]->fecha;

    $id = $sim[0]->id;

    $productos = DB::table('productos')
    ->where('productos.condicion', '=', 1)
    ->get();
    $extras = DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'extras')
    ->where('sv.condicion', '=', 1)
    ->get();
    $ingr_extras=DB::table('ingredientes')->get();


    $productos_sim = DB::table('simulacion_productos as sp')
    ->join('productos as p', 'p.id', '=', 'sp.id_producto')
    ->where('sp.id_simulacion', '=', $id)
    ->get();
    $trabajadores = DB::table('simulacion_trabajadores as st')
    ->where('st.id_simulacion', '=', $id)
    ->get();
    $ingredientes_sim = DB::table('simulacion_ingr_extra as sie')
    ->join('ingredientes as ingr', 'ingr.id', '=', 'sie.id_ingrediente')
    ->where('sie.id_simulacion', '=', $id)
    ->get();
    $extras_sim = DB::table('simulacion_extras as es')
    ->join('selects_valores as sv', 'sv.id', '=', 'es.id_extra')
    ->where('es.id_simulacion', '=', $id)
    ->get();
    $nuevos_sim = DB::table('simulacion_nuevo as sn')
    ->where('sn.id_simulacion', '=', $id)
    ->get();
    $simulacion = DB::table("simulaciones as s")
    ->where("s.id", '=', $id)
    ->get();

    $tipo_instruccion = 4; //Editar evento

    return view('carritos.simulaciones.simulador_edit', ["trabajadores" => $trabajadores,
                                                          "productos_sim" => $productos_sim,
                                                          "productos" => $productos, "id_sim" => $id,
                                                          "ingredientes_sim" => $ingredientes_sim,
                                                          "tipo_instruccion" => $tipo_instruccion,
                                                          "fecha"=>$fecha, "extras_sim" => $extras_sim,
                                                          "ingredientes" => $ingr_extras, "id_evento" => $id_evento,
                                                          "extras" => $extras, "nuevos_sim" => $nuevos_sim,
                                                          "simulacion" => $simulacion]);
  }
}
