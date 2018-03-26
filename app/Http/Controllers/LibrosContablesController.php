<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class LibrosContablesController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
      $this->middleware('admin');
    }
    public function index(){
      return View('carritos.libros_contables.index');

    }
    public function LibroDiario(){
      $carbon = new Carbon('yesterday');

      $cuentas = DB::table('cuentas_contables as cc')
      ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
      //->where('cm.fecha', '=', $carbon)
      ->select('debe', 'haber', 'fecha','nombre_cuenta', 'cm.glosa', 'id_documento')
      ->groupBy('debe', 'haber', 'fecha','nombre_cuenta', 'cm.glosa', 'id_documento')
      ->orderBy('cm.id', 'desc')
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
    public function LibroCompras(){
      $carbon = new Carbon('yesterday');

      $cuentas = DB::table('documento_financiero as df')
      ->join('cuentas_movimientos as cm', 'cm.id_documento', '=', 'df.id')
      ->join('cuentas_contables as cc', 'cc.id', '=', 'cm.id_cuenta')
      ->where('df.tipo_dato', '=', 'compra')
      ->select('debe', 'haber', 'num_prefijo_abs','fecha_documento','nombre_cuenta', 'df.tipo_documento', 'numero_documento','cm.glosa', 'id_documento')
      ->groupBy('debe', 'haber', 'num_prefijo_abs','fecha_documento','nombre_cuenta', 'df.tipo_documento', 'numero_documento','cm.glosa', 'id_documento')
      ->orderBy('df.id', 'asc')
      ->get();

      return View('carritos.libros_contables.libro_compras', ["cuentas"=>$cuentas, "fecha"=>$carbon]);

    }
    public function LibroVentas(){
      $carbon = new Carbon('yesterday');

      $cuentas = DB::table('documento_financiero as df')
      ->join('cuentas_movimientos as cm', 'cm.id_documento', '=', 'df.id')
      ->join('cuentas_contables as cc', 'cc.id', '=', 'cm.id_cuenta')
      ->where('df.tipo_dato', '=', 'venta')
      ->select('debe', 'haber', 'num_prefijo_abs','fecha_documento','nombre_cuenta', 'df.tipo_documento', 'numero_documento','cm.glosa', 'id_documento')
      ->groupBy('debe', 'haber', 'num_prefijo_abs','fecha_documento','nombre_cuenta', 'df.tipo_documento', 'numero_documento','cm.glosa', 'id_documento')
      ->orderBy('df.id', 'asc')
      ->get();

      return View('carritos.libros_contables.libro_ventas', ["cuentas"=>$cuentas, "fecha"=>$carbon]);

    }
}
