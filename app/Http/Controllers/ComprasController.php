<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;
use CamaleonERP\Documento_financiero;
use CamaleonERP\Cuentas_movimientos;
use DB;

class ComprasController extends Controller
{
    public function index(){
        $facturas = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'factura_compra')
        ->join('proveedores as prov', 'prov.id', '=', 'df.id_tercero')
        ->select('df.id', 'tipo_documento', 'df.fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->groupBy('df.id', 'tipo_documento', 'fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->orderBy('id','desc')
        ->paginate(7);
        return View('carritos.compras.index', ["facturas"=>$facturas]);
    }
    public function create(){

      $prov = DB::table('proveedores')
      ->get();
      $cuentas = DB::table('cuentas_contables')
      ->get();
      return View('carritos.compras.create', ["prov"=>$prov, "cuentas"=>$cuentas]);
    }

    public function show($id){
      $factura = DB::table('documento_financiero as df')
      ->join('proveedores as prov', 'prov.id', '=', 'df.id_tercero')
      ->where('df.id', '=', $id)
      ->get();
      $cuentas = DB::table('cuentas_movimientos as cm')
      ->join('cuentas_contables as cc', 'cc.id', '=', 'cm.id_cuenta')
      ->where('cm.id_documento', '=', $id)
      ->get();
      return View('carritos.compras.ver', ["factura"=>$factura, "cuentas"=>$cuentas, "id"=>$id]);
    }

    public function store(Request $request){
      try{
        $id_tercero = $request->get('id_proveedor');
        $tipo_documento = $request->get('tipo_documento');
        $numero_documento = $request->get('numero_documento');
        $fecha_documento = $request->get('fecha_documento');
        $monto_neto = $request->get('monto_neto');
        $iva = $request->get('iva');
        $total = $request->get('total');

        $id_cuenta = $request->get('id_cuenta');
        $debe_cuenta = $request->get('debe_cuenta');
        $haber_cuenta = $request->get('haber_cuenta');
        $glosa_cuenta = $request->get('glosa_cuenta');

        $fact_temp = new Documento_financiero;
        $fact_temp->id_tercero = $id_tercero;
        $fact_temp->tipo_tercero = 'prov';
        $fact_temp->tipo_dato = 'factura_compra';
        $fact_temp->tipo_documento = $tipo_documento;
        $fact_temp->numero_documento = $numero_documento;
        $fact_temp->fecha_documento = $fecha_documento;
        $fact_temp->monto_neto = $monto_neto;
        $fact_temp->iva = $iva;
        $fact_temp->total = $total;
        $fact_temp->save();

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
          $cmf_temp->fecha = $fecha_documento;

          $cmf_temp->save();
          $cont++;
        }

      }catch(Exception $e){
        DB::rollback();
      }
      return Redirect::to("carritos/compras");

    }
}
