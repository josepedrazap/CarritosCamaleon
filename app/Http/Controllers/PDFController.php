<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
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


class PDFController extends Controller
{
    public function crearPDF(){
      $data = DB::table('eventos')->get();
      $view = \View::make('carritos.eventos.pdf.template', compact($data))->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

      return $pdf->stream('reporte');
    }
    public function balance_8_cols($date_1, $date_2){

        $data = DB::table('cuentas_contables as cc')
        ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
        ->whereBetween('fecha', array($date_1, $date_2))
        ->select('nombre_cuenta', 'tipo', DB::raw('sum(debe) as debe'), DB::raw('sum(haber) as haber'))
        ->groupBy('nombre_cuenta', 'tipo')
        ->orderBy('num_prefijo_abs', 'asc')
        ->get();

        $total_debe = DB::table('cuentas_contables as cc')
        ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
        ->whereBetween('fecha', array($date_1, $date_2))
        ->sum('debe');
        $total_haber = DB::table('cuentas_contables as cc')
        ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
        ->whereBetween('fecha', array($date_1, $date_2))
        ->sum('haber');
      $balance_nom ="balance_periodo_".$date_1." a ".$date_2;
      $v = 'carritos.pdf.balance_8_cols';
      $view = \View::make($v, compact('data', 'total_debe', 'total_haber', 'date_1', 'date_2'))->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

      return $pdf->stream($balance_nom);
    }
    public function balance_pdf($date_1, $date_2){

      $gastos_totales = DB::table('gastos') //suma gastos totales brutos
      ->whereBetween('fecha', array($date_1, $date_2))
      ->sum('monto_gasto');
      $iva_total_gastos = DB::table('gastos') //iva de gastos totales
      ->whereBetween('fecha', array($date_1, $date_2))
      ->sum('iva');
      $valor_total_gastos_liquido = DB::table('gastos') //gastos liquidos
      ->whereBetween('fecha', array($date_1, $date_2))
      ->sum('valor_real');

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

      $gastos=DB::table('gastos as gts')
      ->where('gts.condicion', '=', '1')
      ->whereBetween('fecha', array($date_1, $date_2))
      ->orderBy('fecha', 'desc')
      ->get();

      $eventos =DB::table('eventos_detalle as ed')
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->orderBy('fecha_hora', 'desc')
      ->get();

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

      $v = 'carritos.pdf.balance';
      $view = \View::make($v, compact('gastos', 'gastos_totales', 'iva_total_gastos', 'valor_total_gastos_liquido',
                                      'ingreso_eventos_bruto', 'iva_eventos', 'numero_eventos', 'eventos',
                                      'date_1', 'date_2', 'pagos_cocineros', 'cant_pagos_cocineros'))->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

      return $pdf->stream('balance');

    }
    public function despacho_checklist(Request $request){

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

      $otros = DB::table("simulacion_nuevo")->where("id_simulacion", "=", $id_sim)->get();


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
      $fecha = Carbon::now();


      $v = 'carritos.pdf.despacho_checklist';
      $view = \View::make($v, compact('evento', 'fecha','ingredientes', 'otros','ingredientes_extras', 'productos', 'extras'))->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

      return $pdf->stream('reporte');

    }
}
