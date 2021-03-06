<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

//use CamaleonERP\Http\Request;
use CamaleonERP\Productos;
use CamaleonERP\Eventos;
use CamaleonERP\Clientes;
use CamaleonERP\Eventos_tienen_productos;
use CamaleonERP\Eventos_tienen_extras;
use CamaleonERP\Eventos_tienen_ingr_extras;
use Illuminate\Support\Facades\Input;
use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\EventosFormRequest;
use DB;

use Carbon\Carbon;

class EventosController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function index(Request $request){

    $eventos_ejecutados = DB::table('eventos')
    ->where('fecha_hora', '<',  Carbon::now())
    ->where('condicion', '=', 2)
    ->get();

    $cont = 0;

    while($cont < count($eventos_ejecutados)){
      $id = $eventos_ejecutados[$cont]->id;
      $eve_temp = Eventos::findOrFail($id);
      $eve_temp->condicion = 3;
      $eve_temp->update();
      $cont++;
    }


    if($request){

      if($request->get('tipo') == 1){

          $date_1 = new Carbon('next monday');
          $date_1 = $date_1->subWeek(1);
          $date_2 = new Carbon('next monday');
          $date_1 = $date_1->format('Y-m-d');
          $date_2 = $date_2->format('Y-m-d');

          $eventos=DB::table('eventos')
          ->whereIn('condicion', array(1,2,3))
          ->where('fecha_hora', '>=', $date_1)
          ->where('fecha_hora', '<', $date_2)
          ->orderBy('fecha_hora','desc')
          ->get();
          $busq = "Eventos de esta semana";
          return view('carritos.eventos.index',["eventos"=>$eventos, "busq" => $busq,  "tipo"=>1]);
      }
      if($request->get('tipo') == 2){

          $date = Carbon::now();
          $date_now = Carbon::now();
          $date = $date->addDay(1);
          $date_now = $date_now->format('Y-m-d');
          $date = $date->format('Y-m-d');

          $eventos=DB::table('eventos')
          ->whereIn('condicion', array(1,2,3))
          ->where('fecha_hora', '>=', $date_now)
          ->where('fecha_hora', '<', $date)
          ->orderBy('fecha_hora','desc')
          ->get();
          $busq = "Eventos de hoy";
          return view('carritos.eventos.index',["eventos"=>$eventos, "busq" => $busq, "tipo"=>2]);

      }
      if($request->get('tipo') != 1 && $request->get('tipo') != 2){
          $query=trim($request->get('searchText'));
          $eventos=DB::table('eventos')
          ->whereIn('condicion', array(1,2,3))
          ->where('nombre_cliente','LIKE','%'.$query.'%')
          ->orderBy('id','desc')
          ->paginate(7);
          $busq = "Todos los eventos";
          return view('carritos.eventos.index',["eventos"=>$eventos, "busq" => $busq,  "tipo"=>3]);
      }


    }
  }

  public function create(){

        $productos=DB::table('productos')
        ->where('condicion', '=', '1')
        ->orderBy('nombre', 'desc')
        ->get();
        $ingredientes=DB::table('ingredientes as ingr')
        ->where('ingr.condicion', '=', '1')
        ->orderBy('ingr.tipo', 'desc')
        ->get();

        $extras=DB::table('selects_valores as sv')
        ->where('sv.familia', '=', 'extras')
        ->where('sv.condicion', '=', 1)
        ->get();

        $clientes=DB::table('clientes')->get();
        return view('carritos.eventos.create', ["extras"=>$extras, "productos"=>$productos,
                                                "ingredientes"=>$ingredientes, "clientes"=>$clientes]);
  }

  function store(Request $request){
    DB::beginTransaction();
    try{

      $id_cliente = $request->get('cliente');
      $cliente = Clientes::findOrFail($id_cliente);
      $evento = new Eventos;
      $evento->nombre_cliente = $cliente->nombre . " " .$cliente->apellido;
      $evento->email = $cliente->mail;
      $evento->contacto = $cliente->contacto;
      $evento->aprobado = 0;

      $evento->id_cliente = $id_cliente;
      $evento->direccion = $request->get('direccion_cliente');
      $evento->fecha_hora = $request->get('fecha_cliente');
      $evento->fecha_despacho = $request->get('fecha_despacho');
      $evento->descripcion = $request->get('descripcion');

      $i = 0;
      $productos = $request->get('producto');
      $cantidad_p = $request->get('cant_producto');


      if($request->get('condicion') == 1){
            $evento->condicion = 1;
      }
      if($request->get('condicion') == 4){
        $evento->id_cliente = $id_cliente;
        $evento->direccion = "0";
        $evento->fecha_hora = Carbon::now();
        $evento->fecha_despacho = Carbon::now();
        $evento->descripcion = "0";
        $evento->condicion = 4;
      }
      $evento->save();

      while($i < count($productos)){
                $eve_t_prod = new Eventos_tienen_productos;
                $eve_t_prod->id_evento = $evento->id;
                $eve_t_prod->id_producto = $productos[$i];
                $eve_t_prod->cantidad = $cantidad_p[$i];
                $eve_t_prod->precio_a_cobrar = 0;
                $eve_t_prod->save();
                $i++;
      }

      if($request->get('extra')){
            $i = 0;
            $extras = $request->get('extra');
            while($i < count($extras)){
                $eve_t_ext = new Eventos_tienen_extras;
                $eve_t_ext->id_evento = $evento->id;
                $eve_t_ext->id_extra = $extras[$i];
                $eve_t_ext->precio = 0;
                $eve_t_ext->costo = 0;
                $eve_t_ext->save();
                $i++;
            }
        }
        if($request->get('extra_ingr')){
              $i = 0;
              $extras_ingr = $request->get('extra_ingr');
              $cant_extra_ingr = $request->get('cant_ingr');
              while($i < count($extras_ingr)){
                  $eve_t_ingr_ext = new Eventos_tienen_ingr_extras;
                  $eve_t_ingr_ext->id_evento = $evento->id;
                  $eve_t_ingr_ext->id_extra = $extras_ingr[$i];
                  $eve_t_ingr_ext->cantidad = $cant_extra_ingr[$i];
                  $eve_t_ingr_ext->precio = 0;
                  $eve_t_ingr_ext->costo = 0;
                  $eve_t_ingr_ext->cantidad_total = 0;
                  $eve_t_ingr_ext->save();
                  $i++;
              }
          }
      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    if($request->get('condicion') == 4){
      $a = "/carritos/despacho/create?id=".$evento->id;
      return Redirect::to($a);
    }
    return Redirect::to("carritos/eventos");
  }

  function addproductos(){
    return View::make('carritos.eventos.agregarproductos');
  }

  function show($id){

    $evento=DB::table('eventos')
    ->where('eventos.id', '=', $id)
    ->get();

    $ingr_extras=DB::table('ingredientes as ingr')
    ->join('eventos_tienen_ingr_extras as etie', 'etie.id_extra', '=', 'ingr.id')
    ->where('etie.id_evento', '=', $id)
    ->select('ingr.nombre', 'etie.cantidad', 'etie.id', 'ingr.porcion_', 'etie.precio','ingr.uni_porcion')
    ->get();

    $num_ingr_ext=DB::table('ingredientes as ingr')
    ->join('eventos_tienen_ingr_extras as etie', 'etie.id_extra', '=', 'ingr.id')
    ->where('etie.id_evento', '=', $id)
    ->count();

    $evento_detalle=DB::table('eventos_detalle')
    ->where('id_evento', '=', $id)
    ->get();

    $productos=DB::table('productos as prod')
    ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
    ->where('etp.id_evento', '=', $id)
    ->select('prod.nombre', 'etp.cantidad as cantidad', 'precio', 'etp.precio_a_cobrar', 'base', DB::raw('sum(cantidad) as sum'))
    ->groupBy('nombre','cantidad', 'precio', 'base', 'precio_a_cobrar')
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

    $extras=DB::table('eventos_tienen_extras as ete')
    ->join('selects_valores as sv', 'sv.id', '=', 'ete.id_extra')
    ->where('ete.id_evento', '=', $id)
    ->select('sv.valor', 'ete.precio')
    ->groupBy('sv.valor', 'ete.precio')
    ->get();

    if($evento[0]->condicion != 1){

      $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_evento', '=', $id)
      //->where('is.id_evento', '=', $id)
      ->select('ingr.nombre as nombre', 'pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre',  'unidad', 'id_ingr', 'stock', 'uni_inv')
      ->get();

    }else{

      $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_evento', '=', $id)
      ->select('ingr.nombre as nombre',  'pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre',  'unidad', 'id_ingr', 'stock', 'uni_inv')
      ->get();
    }


    $trabajadores=DB::table('trabajadores as tra')
    ->join('eventos_tienen_trabajadores as ett', 'tra.id', '=', 'ett.id_trabajador')
    ->select('tra.nombre as nombre', 'tra.apellido as apellido', 'ett.monto')
    ->where('ett.id_evento', '=', $id)
    ->groupBy('nombre', 'apellido', 'monto')
    ->get();

    $i_ingr = count($ingredientes);
    return view('carritos.eventos.show', ["evento"=>$evento, "ingr_extras"=>$ingr_extras,
                                            "ingredientes"=>$ingredientes,
                                             "productos"=>$productos, "extras"=>$extras,
                                             "trabajadores"=>$trabajadores, "base" => $base,
                                             "total"=>$total,
                                             "pago_cocinero"=>20000, "i_ingr" => $i_ingr,
                                             "evento_detalle"=>$evento_detalle]);
  }

  function edit($id){

    //return view("carritos.eventos.edit", ["producto"=>Productos::findOrFail($id)]);
  }

  function update(EventosRequest $request, $id){

    $producto = FindOrFail($id);
    $producto->Nombre = $request->get('Nombre');
    $producto->Cantidad = $request->get('Cantidad');
    $producto->Tipo = $request->get('Tipo');
    $producto->update();

    return Redirect::to("carritos/eventos");
  }

  function cotizacion(){
    $productos=DB::table('productos')
    ->where('condicion', '=', '1')
    ->orderBy('nombre', 'desc')
    ->get();
    $ingredientes=DB::table('ingredientes as ingr')
    ->where('ingr.condicion', '=', '1')
    ->orderBy('ingr.tipo', 'desc')
    ->get();
    $extras=DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'extras')
    ->where('sv.condicion', '=', 1)
    ->get();


    $clientes=DB::table('clientes')->get();
    return view('carritos.eventos.cotizacion', ["productos"=>$productos, "extras"=>$extras, "ingredientes"=>$ingredientes, "clientes"=>$clientes]);
  }

  function destroy($id){
    $evento=Eventos::findOrFail($id);
    $evento->condicion = 0;
    $evento->update();
    return Redirect::to("carritos/eventos");

  }

}
