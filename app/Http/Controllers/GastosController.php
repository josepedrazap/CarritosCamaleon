<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

//use CamaleonERP\Http\Request;
use CamaleonERP\Gastos;
use CamaleonERP\Eventos;
use CamaleonERP\Clientes;
use CamaleonERP\Documento_financiero;
use CamaleonERP\Cuentas_movimientos;
use CamaleonERP\Eventos_tienen_productos;
use CamaleonERP\Eventos_tienen_extras;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

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

    $date_1 = $request->get('date_1');
    $date_2 = $request->get('date_2');

      $query=trim($request->get('searchText'));
      $data=DB::table('gastos as gts')
      ->where('gts.condicion', '=', '1')
      ->join('documento_financiero as df', 'df.id', '=', 'gts.id_documento')
      ->whereBetween('fecha', array($date_1, $date_2))
      ->get();
      return view('carritos.gastos.index',["data"=>$data, "searchText"=>$query, "date_1"=>$date_1, "date_2"=>$date_2]);


  }
  function create(){
    $prov = DB::table('proveedores')
    ->get();
    $serie_comprobante = Documento_financiero::all();
    $serie = $serie_comprobante->last();

    $cuentas = DB::table('cuentas_contables')
    ->get();
    return View('carritos.gastos.create', ["prov"=>$prov, "cuentas"=>$cuentas, "serie"=>$serie]);
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
    DB::beginTransaction();
    try{
      $id_tercero = -1;
      $tipo_documento = "Boleta";
      $numero_documento = $request->get('numero_documento');
      $fecha_documento = $request->get('fecha_documento');
      $monto_neto = $request->get('monto_neto');
      $iva = $request->get('iva');
      $total = $request->get('total');
      $fecha_ingreso = $request->get('fecha_ingreso');
      $id_cuenta = $request->get('id_cuenta');
      $debe_cuenta = $request->get('debe_cuenta');
      $haber_cuenta = $request->get('haber_cuenta');
      $glosa_cuenta = $request->get('glosa_cuenta');

      $fact_temp = new Documento_financiero;
      $fact_temp->id_tercero = $id_tercero;
      $fact_temp->tipo_tercero = 'prov';
      $fact_temp->tipo_dato = 'gasto';
      $fact_temp->tipo_documento = $tipo_documento;
      $fact_temp->numero_documento = $numero_documento;
      $fact_temp->fecha_documento = $fecha_documento;
      $fact_temp->monto_neto = $monto_neto;
      $fact_temp->iva = $iva;
      $fact_temp->total = $total;
      $fact_temp->fecha_ingreso = $fecha_ingreso;
      $fact_temp->save();

      $nombre_pagador = $request->get('nombre_pagador');
      $descripcion = $request->get('descripcion');

      $gst_temp = new Gastos;
      $gst_temp->id_documento = $fact_temp->id;
      $gst_temp->pagador = $nombre_pagador;
      $gst_temp->condicion = 1;
      $gst_temp->fecha = $fecha_documento;
      $gst_temp->descripcion = $descripcion;
      $gst_temp->save();

      $cont = 0;
      while($cont < count($id_cuenta)){

        $cmf_temp = new Cuentas_movimientos;
        $cmf_temp->id_cuenta = $id_cuenta[$cont];
        $cmf_temp->id_documento = $fact_temp->id;
        if($debe_cuenta[$cont] != ''){
              $cmf_temp->debe =  $debe_cuenta[$cont];
        }else{
              $cmf_temp->debe = 0;
        }
        if($haber_cuenta[$cont] != ''){
              $cmf_temp->haber = $haber_cuenta[$cont];
        }else{
              $cmf_temp->haber = 0;
        }
        if($glosa_cuenta[$cont] != ''){
              $cmf_temp->glosa = $glosa_cuenta[$cont];
        }else{
              $cmf_temp->glosa = '';
        }
        $cmf_temp->fecha = $fecha_ingreso;

        $cmf_temp->save();
        $cont++;
      }
      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    return Redirect::to("carritos/gastos");
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
  public function index_excel ($date_1, $date_2){

    $data=DB::table('gastos as gts')
    ->where('gts.condicion', '=', '1')
    ->join('documento_financiero as df', 'df.id', '=', 'gts.id_documento')
    ->whereBetween('fecha', array($date_1, $date_2))
    ->get();

     $nombre = "gastos_periodo_".$date_1."_a_".$date_2;

     Excel::create($nombre, function($excel) use ($data) {

         $excel->sheet('Ventas', function($sheet) use ($data) {
             $sheet->loadView('carritos.aux_views.5')
             ->with('data', $data);
             $sheet->setOrientation('landscape');
         });

     })->export('xlsx');
  }
}
