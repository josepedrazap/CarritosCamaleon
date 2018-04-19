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

class HonorariosController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(Request $request){

      $date_1 = $request->get('date_1');
      $date_2 = $request->get('date_2');

      $query=trim($request->get('searchText'));
      $data=DB::table('documento_financiero as df')
      ->where('df.tipo_dato', '=', 'honorario')
      ->join('trabajadores as tra', 'tra.id', '=', 'df.id_tercero')
      ->join('trabajador_detalle as td', 'td.id_trabajador', '=', 'tra.id')
      ->whereBetween('fecha_documento', array($date_1, $date_2))
      ->get();
      return view('carritos.honorarios.index',["data"=>$data, "date_1"=>$date_1, "date_2"=>$date_2]);

  }
  function create(){
    $prov = DB::table('proveedores')
    ->get();
    $tra = DB::table('trabajadores as tra')
    ->join('trabajador_detalle as td', 'td.id_trabajador', '=', 'tra.id')
    ->where('tra.condicion', '=', 1)
    ->select('tra.nombre', 'tra.apellido', 'tra.id', 'td.rut')
    ->get();
    $cuentas = DB::table('cuentas_contables')
    ->get();
    return View('carritos.honorarios.create', ["tra"=>$tra, "prov"=>$prov, "cuentas"=>$cuentas]);
  }

  function store(Request $request){
    DB::beginTransaction();
    try{
      $id_tercero = $request->get('id');
      $tipo_documento = "Boleta_honorarios";
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
      $fact_temp->tipo_tercero = 'trab';
      $fact_temp->tipo_dato = 'honorario';
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
      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
    return Redirect::to("carritos/honorarios");
  }

  public function index_excel ($date_1, $date_2){

      $data=DB::table('documento_financiero as df')
      ->where('df.tipo_dato', '=', 'honorario')
      ->join('trabajadores as tra', 'tra.id', '=', 'df.id_tercero')
      ->join('trabajador_detalle as td', 'td.id_trabajador', '=', 'tra.id')
      ->whereBetween('fecha_documento', array($date_1, $date_2))
      ->get();

     $nombre = "honorarios_periodo_".$date_1."_a_".$date_2;

     Excel::create($nombre, function($excel) use ($data) {

         $excel->sheet('Honorarios', function($sheet) use ($data) {
             $sheet->loadView('carritos.aux_views.2')
             ->with('data', $data);
             $sheet->setOrientation('landscape');
         });

     })->export('xlsx');
  }

}
