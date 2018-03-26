<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class LibrosContablesController extends Controller
{
    public function LibroDiario(){
      $carbon = new Carbon('yesterday');

      $cuentas = DB::table('cuentas_contables as cc')
      ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
      //->where('cm.fecha', '=', $carbon)
      ->select('debe', 'haber', 'fecha','nombre_cuenta', 'cm.glosa', 'id_documento')
      ->groupBy('debe', 'haber', 'fecha','nombre_cuenta', 'cm.glosa', 'id_documento')
      ->orderBy('fecha', 'desc')
      ->get();

      return View('carritos.libros_contables.libro_diario', ["cuentas"=>$cuentas, "fecha"=>$carbon]);

    }
    public function LibroMayor(){
      $carbon = new Carbon('yesterday');

      $cuentas = DB::table('cuentas_contables as cc')
      ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
      //->where('cm.fecha', '=', $carbon)
      ->select('debe', 'haber', 'num_prefijo_abs','fecha','nombre_cuenta', 'cm.glosa', 'id_documento')
      ->groupBy('debe', 'haber', 'num_prefijo_abs','fecha','nombre_cuenta', 'cm.glosa', 'id_documento')
      ->orderBy('num_prefijo_abs', 'asc')
      ->get();

      return View('carritos.libros_contables.libro_mayor', ["cuentas"=>$cuentas, "fecha"=>$carbon]);

    }
}
