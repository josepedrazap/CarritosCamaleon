<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use CamaleonERP\Facturas_ventas;
use CamaleonERP\Cuentas_mov_facturas;
use DB;

class ComprasController extends Controller
{
    public function index(){
        $facturas = DB::table('facturas_ventas as fv')
        ->join('proveedores as prov', 'prov.id', '=', 'fv.id_proveedor')
        ->select('fv.id', 'tipo_documento', 'fv.fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->groupBy('fv.id', 'tipo_documento', 'fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
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
      $factura = DB::table('facturas_ventas as fv')
      ->join('proveedores as prov', 'prov.id', '=', 'fv.id_proveedor')
      ->where('fv.id', '=', $id)
      ->get();
      $cuentas = DB::table('cuentas_mov_facturas as cmf')
      ->join('cuentas_contables as cc', 'cc.id', '=', 'cmf.id_cuenta')
      ->where('cmf.id_factura', '=', $id)
      ->get();
      return View('carritos.compras.ver', ["factura"=>$factura, "cuentas"=>$cuentas, "id"=>$id]);
    }

    public function store(Request $request){
      try{
        $id_proveedor = $request->get('id_proveedor');
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

        $fact_temp = new Facturas_ventas;
        $fact_temp->id_proveedor = $id_proveedor;
        $fact_temp->tipo_documento = $tipo_documento;
        $fact_temp->numero_documento = $numero_documento;
        $fact_temp->fecha_documento = $fecha_documento;
        $fact_temp->monto_neto = $monto_neto;
        $fact_temp->iva = $iva;
        $fact_temp->total = $total;
        $fact_temp->save();

        $cont = 0;
        while($cont < count($id_cuenta)){

          $cmf_temp = new Cuentas_mov_facturas;
          $cmf_temp->id_cuenta = $id_cuenta[$cont];
          $cmf_temp->id_factura = $fact_temp->id;
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
