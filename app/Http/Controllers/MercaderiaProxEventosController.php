<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use CamaleonERP\Ingredientes;
use CamaleonERP\Inventario;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\ProductosFormRequest;
use DB;
use Carbon\Carbon;

class MercaderiaProxEventosController extends Controller
{
  public function __construct(){
    $this->middleware('auth');

  }
    public function index(Request $request){

      $date_1 = $request->get('date_1');
      $date_2 = $request->get('date_2');
      $var = 1;
      $ingredientes_totales = DB::table('eventos as eve')
      ->join('eventos_tienen_productos as etp', 'etp.id_evento', '=', 'eve.id')
      ->join('productos as prod', 'prod.id', '=', 'etp.id_producto')
      ->join('productos_tienen_ingredientes as pti', 'pti.id_producto', '=', 'prod.id')
      ->join('ingredientes as ingr', 'ingr.id', '=', 'pti.id_ingrediente')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->whereBetween('eve.fecha_hora', array($date_1, $date_2))
      ->where('eve.condicion', '=', '2')
      ->where('eve.aprobado', '=', '0')
      ->select('ingr.nombre as nombre', 'ingr.id as id_ingr', 'ingr.inventareable as inventareable', 'inv.cantidad as stock', 'pti.unidad as unidad', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre', 'id_ingr', 'unidad', 'inventareable', 'stock')
      ->orderBy('ingr.inventareable', 'desc')
      ->get();

      $ingr_extras = DB::table('eventos_tienen_ingr_extras as etie')
      ->join('ingredientes as ingr', 'ingr.id', '=', 'etie.id_extra')
      ->join('eventos as eve', 'eve.id', '=', 'etie.id_evento')
      ->whereBetween('eve.fecha_hora', array($date_1, $date_2))
      ->where('eve.condicion', '=', '2')
      ->where('eve.aprobado', '=', '0')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->select('ingr.nombre as nombre', 'ingr.id as id_ingr','ingr.inventareable as inventareable', 'inv.cantidad as stock', 'ingr.uni_porcion as unidad', DB::raw('sum(etie.cantidad_total) as sum'))
      ->groupBy('nombre', 'id_ingr','unidad', 'inventareable', 'stock')
      ->orderBy('ingr.inventareable', 'desc')
      ->get();


      $cant_eventos = DB::table('eventos as eve')
      ->whereBetween('eve.fecha_hora', array($date_1, $date_2))
      ->where('eve.condicion', '=', '2')
      ->where('eve.aprobado', '=', '0')
      ->count('eve.id');

      if(!$request->get('date_1') || !$request->get('date_2')){
        $var = 0;
      }

      return view('carritos.mercaderiaproxeventos.index_2', ["ingredientes_totales"=>$ingredientes_totales, "ingredientes_extras" => $ingr_extras,
                                                           "cant_eventos"=>$cant_eventos, "var"=>$var,"date_1"=>$date_1, "date_2"=>$date_2]);

    }
}
