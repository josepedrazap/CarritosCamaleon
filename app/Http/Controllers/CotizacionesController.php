<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

//use CamaleonERP\Http\Request;
use CamaleonERP\Productos;
use CamaleonERP\Eventos;
use CamaleonERP\Clientes;
use CamaleonERP\Eventos_tienen_productos;
use CamaleonERP\Eventos_tienen_extras;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\EventosFormRequest;
use DB;

use Carbon\Carbon;

class CotizacionesController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function index(Request $request){

    if($request){

      if($request->get('tipo') == 1){

          $date_1 = new Carbon('next monday');
          $date_1 = $date_1->subWeek(1);
          $date_2 = new Carbon('next monday');
          $date_1 = $date_1->format('Y-m-d');
          $date_2 = $date_2->format('Y-m-d');

          $eventos=DB::table('eventos')
          ->where('condicion', '=', 4)
          ->where('fecha_hora', '>=', $date_1)
          ->where('fecha_hora', '<', $date_2)
          ->orderBy('fecha_hora','desc')
          ->get();
          $busq = "Cotizacioness de esta semana";
          return view('carritos.cotizaciones.index',["eventos"=>$eventos, "busq" => $busq,  "tipo"=>1]);
      }
      if($request->get('tipo') == 2){

          $date = Carbon::now();
          $date_now = Carbon::now();
          $date = $date->addDay(1);
          $date_now = $date_now->format('Y-m-d');
          $date = $date->format('Y-m-d');

          $eventos=DB::table('eventos')
          ->where('condicion', '=', 4)
          ->where('fecha_hora', '>=', $date_now)
          ->where('fecha_hora', '<', $date)
          ->orderBy('fecha_hora','desc')
          ->get();
          $busq = "Cotizaciones de hoy";
          return view('carritos.cotizaciones.index',["eventos"=>$eventos, "busq" => $busq, "tipo"=>2]);

      }
      if($request->get('tipo') != 1 && $request->get('tipo') != 2){
          $query=trim($request->get('searchText'));
          $eventos=DB::table('eventos')
          ->where('condicion', '=', 4)
          ->where('nombre_cliente','LIKE','%'.$query.'%')
          ->orderBy('id','desc')
          ->paginate(7);
          $busq = "Todos las cotizaciones";
          return view('carritos.cotizaciones.index',["eventos"=>$eventos, "busq" => $busq,  "tipo"=>3]);
      }
    }
  }

  function store(EventosFormRequest $request){
    DB::beginTransaction();

    try{

      $id_cliente = $request->get('cliente');
      $cliente = Clientes::findOrFail($id_cliente);
      $evento = new Eventos;
      $evento->nombre_cliente = $cliente->nombre;
      $evento->email = $cliente->mail;
      $evento->contacto = $cliente->contacto;
      $evento->condicion = 4;
      $evento->id_cliente = $id_cliente;
      $evento->direccion = $request->get('direccion_cliente');
      $evento->fecha_hora = $request->get('fecha_cliente');
      $evento->fecha_despacho = $request->get('fecha_despacho');
      $evento->save();

      if($request->get('producto')){
            $i = 0;
            $productos = $request->get('producto');
            $cantidad_p = $request->get('cant_producto');
            while($i < count($productos)){
                $eve_t_prod = new Eventos_tienen_productos;
                $eve_t_prod->id_evento = $evento->id;
                $eve_t_prod->id_producto = $productos[$i];
                $eve_t_prod->cantidad = $cantidad_p[$i];
                $eve_t_prod->save();
                $i++;
            }
      }
      if($request->get('extra')){
            $i = 0;
            $extras = $request->get('extra');
            $cantidad_e = $request->get('cant_extra');
            while($i < count($extras)){
                $eve_t_ext = new Eventos_tienen_extras;
                $eve_t_ext->id_evento = $evento->id;
                $eve_t_ext->id_extra = $extras[$i];
                $eve_t_ext->cantidad = $cantidad_e[$i];
                $eve_t_ext->save();
                $i++;
            }
        }
        DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    return Redirect::to("carritos/cotizaciones");
  }

  function addproductos(){
    return View::make('carritos.cotizaciones.agregarproductos');
  }

  public function create(Request $request){

    $id = $request->id;

    $evento=DB::table('eventos')
    ->where('eventos.id', '=', $id)
    ->paginate(7);

    $productos=DB::table('productos as prod')
    ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_evento', '=', $id)
    ->select('prod.nombre', 'etp.cantidad as cantidad', 'precio', 'base', DB::raw('sum(cantidad) as sum'))
    ->groupBy('nombre','cantidad', 'precio', 'base')
    ->get();

    $base=DB::table('productos as prod')
    ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_evento', '=', $id)
    ->select('base', DB::raw('sum(cantidad) as sum'))
    ->groupBy('base')
    ->get();

    $total=DB::table('productos as prod')
    ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_evento', '=', $id)
    ->select( 'etp.id_evento as id_e', DB::raw('sum(cantidad*precio) as sum'))
    ->groupBy('id_e')
    ->get();

    $extras=DB::table('ingredientes as ingr')
    ->join('eventos_tienen_extras as ete', 'ingr.id', '=', 'ete.id_extra')
    ->where('ete.id_evento', '=', $id)
    ->select('ingr.nombre', 'ete.cantidad')
    ->groupBy('nombre', 'cantidad')
    ->get();

    $ingredientes=DB::table('productos as prod')
    ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
    ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
    ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
    ->where('etp.id_evento', '=', $id)
    ->select('ingr.nombre as nombre', 'ingr.inventareable' ,'pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
    ->groupBy('nombre', 'ingr.inventareable' ,'unidad', 'id_ingr', 'stock', 'uni_inv')
    ->get();

    $ingredientes_producto=DB::table('productos as prod')
    ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
    ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
    ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_evento', '=', $id)
    ->select('prod.nombre as nombre','pti.unidad as unidad', 'ingr.unidad as uni_inv', 'ingr.id as id_ingr')
    ->groupBy('nombre' ,'unidad', 'id_ingr', 'uni_inv')
    ->get();

    $trabajadores=DB::table('trabajadores as tra')
    ->join('trabajador_detalle as td', 'tra.id', '=', 'td.id_trabajador')
    ->where('tra.condicion', '=', 1)
    ->select('tra.nombre as nombre', 'td.maneja', 'tra.id as id', 'tra.apellido as apellido')
    ->groupBy('nombre', 'maneja', 'id', 'apellido')
    ->get();

    $vehiculos=DB::table('vehiculos')->get();

    $i_ingr = count($ingredientes);
    return view('carritos.cotizaciones.create', ["evento"=>$evento, "ingredientes"=>$ingredientes,
                                             "productos"=>$productos, "extras"=>$extras,
                                             "trabajadores"=>$trabajadores, "base" => $base,
                                             "total"=>$total, "vehiculos"=>$vehiculos,
                                             "pago_cocinero"=>20000, "i_ingr" => $i_ingr,
                                             "igredientes_producto"=>$ingredientes_producto]);
  }

  function edit($id){

    return view("carritos.cotizaciones.edit", ["producto"=>Productos::findOrFail($id)]);
  }

  function update(EventosRequest $request, $id){

    $producto = FindOrFail($id);
    $producto->Nombre = $request->get('Nombre');
    $producto->Cantidad = $request->get('Cantidad');
    $producto->Tipo = $request->get('Tipo');
    $producto->update();

    return Redirect::to("carritos/cotizaciones");
  }

  function destroy($id){

    $evento=Eventos::findOrFail($id);
    $evento->condicion = 0;
    $evento->update();
    return Redirect::to("carritos/cotizaciones");

  }

}
