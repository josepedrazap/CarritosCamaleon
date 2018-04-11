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
    public function despacho_checklist($id){

      $evento=DB::table('eventos')
      ->where('eventos.id', '=', $id)
      ->get();

      $ingr_extras=DB::table('ingredientes as ingr')
      ->join('eventos_tienen_ingr_extras as etie', 'etie.id_extra', '=', 'ingr.id')
      ->where('etie.id_evento', '=', $id)
      ->select('ingr.nombre', 'etie.cantidad', 'etie.id', 'ingr.porcion_', 'ingr.precio_bruto','ingr.uni_porcion')
      ->get();

      $num_ingr_ext=DB::table('ingredientes as ingr')
      ->join('eventos_tienen_ingr_extras as etie', 'etie.id_extra', '=', 'ingr.id')
      ->where('etie.id_evento', '=', $id)
      ->count();

      $productos=DB::table('productos as prod')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->where('etp.id_evento', '=', $id)
      ->select('prod.nombre', 'etp.cantidad as cantidad', 'precio', 'etp.id as id_etp', 'base', DB::raw('sum(cantidad) as sum'))
      ->groupBy('nombre','cantidad', 'precio', 'base', 'id_etp')
      ->get();

      $base=DB::table('productos as prod')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->where('etp.id_evento', '=', $id)
      ->select('base', DB::raw('sum(cantidad) as sum'))
      ->groupBy('base')
      ->get();

      $extras=DB::table('eventos_tienen_extras as ete')
      ->join('selects_valores as sv', 'sv.id', '=','ete.id_extra')
      ->where('ete.id_evento', '=', $id)
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

      $v = 'carritos.pdf.despacho_checklist';
      $view = \View::make($v, compact('evento', 'num_ingr_ext','ingredientes', 'productos', 'base', 'extras', 'ingr_extras'))->render();
      $pdf = \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);

      return $pdf->stream('reporte');

    }
}
