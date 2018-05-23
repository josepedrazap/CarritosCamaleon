<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;
use CamaleonERP\Documento_financiero;
use CamaleonERP\Cuentas_movimientos;
use Maatwebsite\Excel\Facades\Excel;

use DB;

class AjustesController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
    public function index(Request $request){

      $date_1 = $request->get('date_1');
      $date_2 = $request->get('date_2');
      $num_com = $request->get('num_com');

      if($num_com != ''){
        $facturas = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'ajuste')
        ->where('numero_comprobante', '=', $num_com)
        ->orderBy('id','desc')
        ->get();
      }else{
        $facturas = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'ajuste')
        ->whereBetween('fecha_ingreso', array($date_1, $date_2))
        ->orderBy('id','desc')
        ->get();
      }
        return View('carritos.ajustes.index', ["facturas"=>$facturas, "num_com"=>$num_com,"date_1"=>$date_1, "date_2"=>$date_2]);
    }
    public function create(){

      $serie = DB::table('documento_financiero as df')
      ->max('df.numero_comprobante');
      $cuentas = DB::table('cuentas_contables')
      ->get();
      return View('carritos.ajustes.create', [ "cuentas"=>$cuentas, "serie"=>$serie]);
    }
    public function show($id){
      $doc = DB::table('documento_financiero as dc')
      ->where('dc.id', '=', $id)
      ->get();

      $cuentas = DB::table('cuentas_contables')
      ->get();

      $ct_usadas = DB::table('cuentas_movimientos as cm')
      ->where('cm.id_documento', '=', $id)
      ->join('cuentas_contables as cc', 'cm.id_cuenta', '=', 'cc.id')
      ->select('cm.debe', 'cm.haber', 'cm.glosa', 'cc.nombre_cuenta', 'cm.id_cuenta')
      ->get();

      $cont = count($ct_usadas);

      $total_debe = DB::table('cuentas_movimientos as cm')
      ->where('cm.id_documento', '=', $id)
      ->sum('debe');

      $total_haber = DB::table('cuentas_movimientos as cm')
      ->where('cm.id_documento', '=', $id)
      ->sum('haber');

      return View('carritos.ajustes.edit', [ "cuentas"=>$cuentas, "cont"=>$cont, "id"=>$id,
                                             "cuentas_usadas"=>$ct_usadas, "total_debe"=>$total_debe,
                                             "total_haber"=>$total_haber, "doc"=>$doc]);
    }
    public function editar(Request $request){
      DB::beginTransaction();
      try{
        $id = $request->get('id_documento');
        $dc_tmp = Documento_financiero::findOrFail($id);
        $dc_tmp->numero_comprobante = $request->get('numero_comprobante');
        $dc_tmp->fecha_ingreso = $request->get('fecha_ingreso');
        $dc_tmp->update();

        $id_cuenta = $request->get('id_cuenta');
        $debe_cuenta = $request->get('debe_cuenta');
        $haber_cuenta = $request->get('haber_cuenta');
        $glosa_cuenta = $request->get('glosa_cuenta');

        $d = Cuentas_movimientos::where('id_documento', '=', $id)->delete();

        $cont = 0;
        while($cont < count($id_cuenta)){

          $cmf_temp = new Cuentas_movimientos;
          $cmf_temp->id_cuenta = $id_cuenta[$cont];
          $cmf_temp->id_documento = $id;
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
          $cmf_temp->fecha = $dc_tmp->fecha_ingreso;

          $cmf_temp->save();
          $cont++;
        }

        DB::commit();
      }catch(Exception $e){
        DB::rollback();
      }
        return Redirect::to("carritos/ajustes");
    }
    public function store(Request $request){
      DB::beginTransaction();

      try{

        $tipo_documento = 'no aplica';
        $numero_documento = 0;
        $fecha_documento = $request->get('fecha_ingreso');
        $monto_neto = 0;
        $iva = 0;
        $total = 0;
        $fecha_ingreso = $request->get('fecha_ingreso');
        $id_cuenta = $request->get('id_cuenta');
        $debe_cuenta = $request->get('debe_cuenta');
        $haber_cuenta = $request->get('haber_cuenta');
        $glosa_cuenta = $request->get('glosa_cuenta');
        $otros_impuestos = 0;
        $excento = 0;

        $fact_temp = new Documento_financiero;
        $fact_temp->id_tercero = -1;
        $fact_temp->tipo_tercero = 'prov';
        $fact_temp->tipo_dato = 'ajuste';
        $fact_temp->tipo_documento = $tipo_documento;
        $fact_temp->numero_documento = $numero_documento;
        $fact_temp->fecha_documento = $fecha_documento;
        $fact_temp->fecha_ingreso = $fecha_ingreso;
        $fact_temp->monto_neto = $monto_neto;
        $fact_temp->iva = $iva;
        $fact_temp->total = $total;

        $num_ = $request->get('numero_comprobante');

        $fact_temp->numero_comprobante = $num_;
        $fact_temp->excento = $excento;
        $fact_temp->otros_impuestos;
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
          $cmf_temp->fecha = $fecha_ingreso;

          $cmf_temp->save();
          $cont++;
        }
        DB::commit();

      }catch(Exception $e){
        DB::rollback();
      }
      return Redirect::to("carritos/ajustes");

    }
    public function index_excel ($date_1, $date_2){

        $data = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'ajuste')
        ->join('proveedores as prov', 'prov.id', '=', 'df.id_tercero')
        ->whereBetween('fecha_ingreso', array($date_1, $date_2))
        ->select('df.id', 'tipo_documento', 'df.fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->groupBy('df.id', 'tipo_documento', 'fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->orderBy('id','desc')
        ->get();

       $nombre = "compras_periodo_".$date_1."_a_".$date_2;

       Excel::create($nombre, function($excel) use ($data) {

           $excel->sheet('Honorarios', function($sheet) use ($data) {
               $sheet->loadView('carritos.aux_views.6')
               ->with('data', $data);
               $sheet->setOrientation('landscape');
           });

       })->export('xlsx');
    }
    public function axios_pnc(Request $request){
      $num = $request->get('serie_comprobante');
      $data = DB::table('documento_financiero as df')
      ->where('df.numero_comprobante', '=', $num)
      ->sum('df.numero_comprobante');
      return $data;
    }
    public function axios_onc(){
      $data = DB::table('documento_financiero as df')
      ->max('df.numero_comprobante');
      return $data;
    }
}
