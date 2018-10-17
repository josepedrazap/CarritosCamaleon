<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\Simulaciones;
use CamaleonERP\Simulacion_productos;
use CamaleonERP\Simulacion_extras;
use CamaleonERP\Simulacion_ingr_extra;
use CamaleonERP\Simulacion_nuevo;

use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\UserFormRequest;
use DB;


class simulacionesController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }

  public function simulador(){

    $productos = DB::table('productos')->get();
    $extras = DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'extras')
    ->where('sv.condicion', '=', 1)
    ->get();
    $ingr_extras=DB::table('ingredientes')->get();

    return view('carritos.simulaciones.simulador', ["productos" => $productos, "ingredientes" => $ingr_extras, "extras" => $extras]);
  }

  function store(Request $request){
    DB::beginTransaction();
    try{

      $simulacion = new Simulaciones;
      $simulacion->nombre = $request->get('nombre');
      $simulacion->fecha = $request->get('fecha');
      $simulacion->descripcion = $request->get('descripcion');
      $simulacion->save();

      if($request->get('producto')){
        $i = 0;
        $productos = $request->get('producto');
        $cantidad_p = $request->get('cant_producto');
        while($i < count($productos)){
                  $sim_prod = new Simulacion_productos;
                  $sim_prod->id_simulacion = $simulacion->id;
                  $sim_prod->id_producto = $productos[$i];
                  $sim_prod->cantidad = $cantidad_p[$i];
                  $sim_prod->precio_neto_unidad = 0;
                  $sim_prod->save();
                  $i++;
        }
      }
      if($request->get('extra')){
            $i = 0;
            $extras = $request->get('extra');
            while($i < count($extras)){
                $sim_extras = new Simulacion_extras;
                $sim_extras->id_simulacion = $simulacion->id;
                $sim_extras->id_extra = $extras[$i];
                $sim_extras->precio_neto_unidad = 0;
                $sim_extras->costo_neto_unidad = 0;
                $sim_extras->save();
                $i++;
            }
        }
      if($request->get('nuevo')){
            $i = 0;
            $nuevo = $request->get('nuevo');
            $cant_nuevo = $request->get('cant_nuevo');
            while($i < count($nuevo)){
                $sim_nuevo = new Simulacion_nuevo;
                $sim_nuevo->id_simulacion = $simulacion->id;
                $sim_nuevo->nombre = $nuevo[$i];
                $sim_nuevo->cantidad = $cant_nuevo[$i];
                $sim_nuevo->precio_neto_unidad = 0;
                $sim_nuevo->costo_neto_unidad = 0;
                $sim_nuevo->save();
                $i++;
            }
      }
      if($request->get('extra_ingr')){
              $i = 0;
              $extras_ingr = $request->get('extra_ingr');
              $cant_extra_ingr = $request->get('cant_ingr');
              while($i < count($extras_ingr)){
                  $sim_ingr_extra = new Simulacion_ingr_extra;
                  $sim_ingr_extra->id_simulacion = $simulacion->id;
                  $sim_ingr_extra->id_extra = $extras_ingr[$i];
                  $sim_ingr_extra->cantidad = $cant_extra_ingr[$i];
                  $sim_ingr_extra->precio_neto_unidad = 0;
                  $sim_ingr_extra->costo_neto_unidad = 0;
                  $sim_ingr_extra->save();
                  $i++;
              }
      }
      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    $a = "/carritos/simulaciones/create?id=".$simulacion->id;
    return Redirect::to($a);
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


}
