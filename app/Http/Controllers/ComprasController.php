<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;
use CamaleonERP\Documento_financiero;
use CamaleonERP\Cuentas_movimientos;
use Maatwebsite\Excel\Facades\Excel;

use DB;

class ComprasController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
    public function index(Request $request){

      $date_1 = $request->get('date_1');
      $date_2 = $request->get('date_2');

        $facturas = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'compra')
        ->join('proveedores as prov', 'prov.id', '=', 'df.id_tercero')
        ->whereBetween('fecha_ingreso', array($date_1, $date_2))
        ->select('df.id', 'tipo_documento', 'excento', 'otros_impuestos','df.fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->groupBy('df.id', 'tipo_documento', 'excento', 'otros_impuestos', 'fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->orderBy('id','desc')
        ->paginate(7);
        return View('carritos.compras.index', ["facturas"=>$facturas, "date_1"=>$date_1, "date_2"=>$date_2]);
    }
    public function create(){

      $prov = DB::table('proveedores')
      ->get();
      $serie_comprobante = Documento_financiero::all();
      $serie = $serie_comprobante->last();
      $cuentas = DB::table('cuentas_contables')
      ->get();
      return View('carritos.compras.create', ["prov"=>$prov, "cuentas"=>$cuentas, "serie"=>$serie]);
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
      DB::beginTransaction();

      try{
        $id_tercero = $request->get('id_proveedor');
        $tipo_documento = $request->get('tipo_documento');
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
        $otros_impuestos = $request->get('otros_impuestos');
        $excento = $request->get('excento');

        $fact_temp = new Documento_financiero;
        $fact_temp->id_tercero = $id_tercero;
        $fact_temp->tipo_tercero = 'prov';
        $fact_temp->tipo_dato = 'compra';
        $fact_temp->tipo_documento = $tipo_documento;
        $fact_temp->numero_documento = $numero_documento;
        $fact_temp->fecha_documento = $fecha_documento;
        $fact_temp->fecha_ingreso = $fecha_ingreso;
        $fact_temp->monto_neto = $monto_neto;
        $fact_temp->iva = $iva;
        $fact_temp->total = $total;
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
          $cmf_temp->fecha = $fecha_documento;

          $cmf_temp->save();
          $cont++;
        }
        DB::commit();

      }catch(Exception $e){
        DB::rollback();
      }
      return Redirect::to("carritos/compras");

    }
    public function index_excel ($date_1, $date_2){

        $data = DB::table('documento_financiero as df')
        ->where('tipo_dato', '=', 'compra')
        ->join('proveedores as prov', 'prov.id', '=', 'df.id_tercero')
        ->whereBetween('fecha_documento', array($date_1, $date_2))
        ->select('df.id', 'tipo_documento', 'df.fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->groupBy('df.id', 'tipo_documento', 'fecha_documento','numero_documento', 'monto_neto', 'iva', 'total', 'rut')
        ->orderBy('id','desc')
        ->get();

       $nombre = "compras_periodo_".$date_1."_a_".$date_2;

       Excel::create($nombre, function($excel) use ($data) {

           $excel->sheet('Honorarios', function($sheet) use ($data) {
               $sheet->loadView('carritos.aux_views.4')
               ->with('data', $data);
               $sheet->setOrientation('landscape');
           });

       })->export('xlsx');
    }
}
