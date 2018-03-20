<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

//use CamaleonERP\Http\Request;
use CamaleonERP\Gastos;
use CamaleonERP\Eventos;
use CamaleonERP\Clientes;
use CamaleonERP\Eventos_tienen_productos;
use CamaleonERP\Eventos_tienen_extras;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\EventosFormRequest;
use DB;

use Carbon\Carbon;

class GastosController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(Request $request){

    $mes = $request->get('mes');
    $año = $request->get('año');

    if($año == 0){
      return view('carritos.gastos.error');
    }else if($mes == 0){
      $date_1 = Carbon::create($año, 1, 1);
      $date_2 = Carbon::create($año, 1, 1);
      $date_2->addYear(1);
      $date_1 = $date_1->format('Y-m-d');
      $date_2 = $date_2->format('Y-m-d');
    }else{
      $date_1 = Carbon::create($año, $mes, 1);
      $date_2 = Carbon::create($año, $mes, 1);
      $date_2->addMonth();
      $date_1 = $date_1->format('Y-m-d');
      $date_2 = $date_2->format('Y-m-d');
    }

      $query=trim($request->get('searchText'));
      $data=DB::table('gastos as gts')
      ->where('gts.condicion', '=', '1')
      ->whereBetween('fecha', array($date_1, $date_2))
      ->get();
      return view('carritos.gastos.index',["data"=>$data, "searchText"=>$query]);


  }
  function create(){
      $tipos=DB::table('gastos_tipo as gt')->get();
      return view('carritos.gastos.create', ["gst"=>$tipos]);
  }

  function resumen(Request $request){

    $mes = $request->get('mes');
    $año = $request->get('año');

    if($año == 0){
      return view('carritos.gastos.error');
    }else if($mes == 0){
      $date_1 = Carbon::create($año, 1, 1);
      $date_2 = Carbon::create($año, 1, 1);
      $date_2->addYear(1);
      $date_1 = $date_1->format('Y-m-d');
      $date_2 = $date_2->format('Y-m-d');
    }else{
      $date_1 = Carbon::create($año, $mes, 1);
      $date_2 = Carbon::create($año, $mes, 1);
      $date_2->addMonth();
      $date_1 = $date_1->format('Y-m-d');
      $date_2 = $date_2->format('Y-m-d');
    }

    $condicional = DB::table('eventos as eve') //numero de eventos no aprobados
    ->where('eve.aprobado', '=', 0)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->count('eve.id');

    $gastos_totales = DB::table('gastos') //suma gastos totales brutos
    ->whereBetween('fecha', array($date_1, $date_2))
    ->sum('monto_gasto');
    $iva_total_gastos = DB::table('gastos') //iva de gastos totales
    ->whereBetween('fecha', array($date_1, $date_2))
    ->sum('iva');
    $valor_total_gastos_liquido = DB::table('gastos') //gastos liquidos
    ->whereBetween('fecha', array($date_1, $date_2))
    ->sum('valor_real');
    $numero_gastos =  DB::table('gastos') //gastos liquidos
    ->whereBetween('fecha', array($date_1, $date_2))
    ->count('id');

    $ingreso_eventos_bruto = DB::table('eventos_detalle as ed') //suma ingreso eventos
    ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
    ->whereIn('eve.condicion', [2,3])
    ->where('eve.aprobado', '=', 1)
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->sum('precio_evento');

    $iva_eventos = $ingreso_eventos_bruto * 0.19; //iva de eventos

    $numero_eventos = DB::table('eventos_detalle as ed') //numero de eventos
    ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
    ->where('eve.aprobado', '=', 1)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->count('eve.id');

    $gasto_eventos_extra = DB::table('eventos_detalle as ed') //suma eventos extra
    ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
    ->where('eve.aprobado', '=', 1)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->sum('gasto_extra');

    $gasto_eventos_bruto_ingr = DB::table('eventos_detalle as ed') //suma gastos eventos brutos en ingredientes
    ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
    ->where('eve.aprobado', '=', 1)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->sum('total_ingredientes');

    $costo_eventos = DB::table('eventos_detalle as ed') //suma gastos eventos brutos en ingredientes
    ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
    ->where('eve.aprobado', '=', 1)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->sum('costo_final');

    $gasto_eventos_iva_ingr = $gasto_eventos_bruto_ingr * 0.19; //iva de suma de gastos
    $gasto_eventos_liquido_ingr = $gasto_eventos_bruto_ingr - $gasto_eventos_iva_ingr; //suma de gastos liquidos

    $iva_ajustado_eventos = $iva_eventos - $gasto_eventos_iva_ingr; //iva ajustado por pagar menos por cobrar

    $pagos_cocineros = DB::table('eventos_tienen_trabajadores as ett') //pagos cocineros totales
    ->join('eventos as eve', 'eve.id', '=', 'ett.id_evento')
    ->where('eve.aprobado', '=', 1)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->sum('monto');
    $cant_pagos_cocineros = DB::table('eventos_tienen_trabajadores as ett') //numero de pagos de cocineros efectuados en el periodo
    ->join('eventos as eve', 'eve.id', '=', 'ett.id_evento')
    ->where('eve.aprobado', '=', 1)
    ->whereIn('eve.condicion', [2,3])
    ->whereBetween('fecha_hora', array($date_1, $date_2))
    ->count('monto');

    $gasto_final = $pagos_cocineros + $gastos_totales;
    $iva_final = $iva_eventos - $iva_total_gastos;
    $utilidad_final = $ingreso_eventos_bruto - $gasto_final - $iva_final;

    return view('carritos.gastos.resumen',["gastos_totales"=>$gastos_totales, "iva_total_gastos"=>$iva_total_gastos,
                                           "valor_total_gastos_liquido"=>$valor_total_gastos_liquido,
                                           "ingreso_eventos_bruto"=>$ingreso_eventos_bruto, "numero_gastos"=>$numero_gastos,
                                           "iva_eventos"=>$iva_eventos, "numero_eventos"=>$numero_eventos,
                                           "pagos_cocineros"=>$pagos_cocineros, "cant_pagos_cocineros"=>$cant_pagos_cocineros,
                                           "date_1"=>$date_1, "date_2"=>$date_2, "utilidad_final" => $utilidad_final,
                                           "gasto_final"=>$gasto_final, "iva_final" => $iva_final,
                                           "gasto_eventos_bruto_ingr" =>$gasto_eventos_bruto_ingr, "gasto_eventos_extra"=>$gasto_eventos_extra,
                                           "gasto_eventos_iva_ingr"=>$gasto_eventos_iva_ingr, "condicional"=>$condicional]);

  }
  function store(Request $request){

    try{
      $tipo = $request->get('tipo');
      $fecha = $request->get('fecha');
      $monto = $request->get('monto');
      $iva = $request->get('iva');
      $total = $request->get('total');
      $nombre_pagador = $request->get('nombre_pagador');
      $descripcion = $request->get('descripcion');

      $gst_temp = new Gastos;
      $gst_temp->pagador = $nombre_pagador;
      $gst_temp->condicion = 1;
      $gst_temp->tipo = $tipo;
      $gst_temp->fecha = $fecha;
      $gst_temp->monto_gasto = $monto;
      $gst_temp->iva = $iva;
      $gst_temp->valor_real = $total;
      $gst_temp->descripcion = $descripcion;

      $gst_temp->save();
    }catch(Exception $e){
      DB::rollback();
    }
    return Redirect::to('carritos/gastos?año=1');
  }
  public function pagador_empresa($id){
    $gst_temp = Gastos::findOrFail($id);
    $gst_temp->pagador = 'Empresa';
    $gst_temp->update();
    return Redirect::to('carritos/gastos');
  }
  public function crearPDF(){
    $data = DB::table('eventos')->get();
    $view = \View::make('carritos.eventos.pdf.template', compact($data))->render();
    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML($view);

    return $pdf->stream('reporte');
  }
}
