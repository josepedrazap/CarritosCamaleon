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

      $date_1 = Carbon::now();
      $date_2 = Carbon::now();
      $date_2->addDays($request->dias);
      $date_1 = $date_1->format('Y-m-d');
      $date_2 = $date_2->format('Y-m-d');

      $ingredientes_totales = DB::table('eventos as eve')
      ->join('eventos_tienen_productos as etp', 'etp.id_evento', '=', 'eve.id')
      ->join('productos as prod', 'prod.id', '=', 'etp.id_producto')
      ->join('productos_tienen_ingredientes as pti', 'pti.id_producto', '=', 'prod.id')
      ->join('ingredientes as ingr', 'ingr.id', '=', 'pti.id_ingrediente')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->whereBetween('eve.fecha_hora', array($date_1, $date_2))
      ->where('eve.condicion', '=', '2')
      ->where('eve.aprobado', '=', '0')
      ->select('ingr.nombre as nombre', 'ingr.inventareable as inventareable', 'inv.cantidad as stock', 'pti.unidad as unidad', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre', 'unidad', 'inventareable', 'stock')
      ->orderBy('ingr.inventareable', 'desc')
      ->get();

      $cant_eventos = DB::table('eventos as eve')
      ->whereBetween('eve.fecha_hora', array($date_1, $date_2))
      ->where('eve.condicion', '=', '2')
      ->where('eve.aprobado', '=', '0')
      ->count('eve.id');

      return view('carritos.mercaderiaproxeventos.index', ["ingredientes_totales"=>$ingredientes_totales,
                                                           "cant_eventos"=>$cant_eventos, "date_1"=>$date_1, "date_2"=>$date_2]);

    }
}
