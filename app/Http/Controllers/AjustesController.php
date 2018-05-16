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

        $facturas = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'ajuste')
        ->whereBetween('fecha_ingreso', array($date_1, $date_2))
        ->orderBy('id','desc')
        ->paginate(7);
        return View('carritos.ajustes.index', ["facturas"=>$facturas, "date_1"=>$date_1, "date_2"=>$date_2]);
    }
    public function create(){

      $serie_comprobante = Documento_financiero::all();
      $serie = $serie_comprobante->last();
      $cuentas = DB::table('cuentas_contables')
      ->get();
      return View('carritos.ajustes.create', [ "cuentas"=>$cuentas, "serie"=>$serie]);
    }

    public function show($id){
      $fecha_ingreso = DB::table('documento_financiero as df')
      ->where('df.id', '=', $id)
      ->select('fecha_ingreso')
      ->get();
      $cuentas = DB::table('cuentas_movimientos as cm')
      ->join('cuentas_contables as cc', 'cc.id', '=', 'cm.id_cuenta')
      ->where('cm.id_documento', '=', $id)
      ->get();
      return View('carritos.ajustes.ver', ["fecha_ingreso"=>$fecha_ingreso, "cuentas"=>$cuentas, "id"=>$id]);
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
        $fact_temp->numero_comprobante = $request->get('numero_comprobante');
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
}
