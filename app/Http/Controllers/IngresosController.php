<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use CamaleonERP\Eventos;
use CamaleonERP\Clientes;
use CamaleonERP\Documento_financiero;
use CamaleonERP\Cuentas_movimientos;
use CamaleonERP\Eventos_tienen_documentos;
use Illuminate\Support\Facades\Redirect;

class IngresosController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
      public function index(Request $request){

        if($request){
          $query=trim($request->get('searchText'));
          $data=DB::table('eventos as eve')
          ->where('eve.aprobado','=','1')
          ->join('eventos_detalle as ed', 'ed.id_evento', '=', 'eve.id')
          ->select('eve.id','eve.nombre_cliente', 'ed.precio_evento', 'eve.fecha_hora')
          ->groupBy('eve.id', 'nombre_cliente', 'precio_evento', 'fecha_hora')

          ->paginate(7);

          return view('carritos.ingresos.index', ["data"=>$data]);
        }
      }
      public function show_2($id){
        $factura = DB::table('documento_financiero as df')

        ->where('df.id', '=', $id)
        ->get();
        $cuentas = DB::table('cuentas_movimientos as cm')
        ->join('cuentas_contables as cc', 'cc.id', '=', 'cm.id_cuenta')
        ->where('cm.id_documento', '=', $id)
        ->get();
        return View('carritos.ingresos.ver', ["factura"=>$factura, "cuentas"=>$cuentas, "id"=>$id]);
      }

      public function mostrar_ingresos(){
        $data=DB::table('documento_financiero as df')
        ->where('df.tipo_dato', '=', 'venta')
        //->join('cuentas_movimientos as cm', 'cm.id_documento', '=', 'df.id')
        //->join('cuentas_contables as cc', 'cc.id', '=', 'cm.id_cuenta')
        ->join('clientes as cli', 'cli.id', '=', 'df.id_tercero')
        ->join('eventos_tienen_documentos as etd', 'etd.id_documento', '=', 'df.id')
        ->join('eventos as eve', 'eve.id', '=', 'etd.id_evento')
        ->select('numero_documento', 'df.id as id_doc','eve.id as id_eve','fecha_hora', 'direccion','fecha_documento','tipo_documento','cli.rut', 'nombre', 'apellido','monto_neto', 'iva', 'total')
        ->groupBy('numero_documento', 'id_doc', 'id_eve','fecha_hora', 'direccion','fecha_documento','tipo_documento','cli.rut', 'nombre','apellido','monto_neto', 'iva', 'total')
        ->orderBy('id_doc', 'asc')
        ->get();

        return view('carritos.ingresos.mostrar_ingresos', ["data"=>$data]);
      }
      public function show($id){
        $eventos_detalle = DB::table('eventos_detalle as ed')
        ->where('ed.id_evento', '=', $id)
        ->get();
        $eci = DB::table('eventos_costo_ingredientes as eci')
        ->join('ingredientes as ingr', 'ingr.id', '=', 'eci.id_ingrediente')
        ->where('eci.id_evento', '=', $id)
        ->get();
        $extras=DB::table('eventos_tienen_extras as ete')
        ->join('selects_valores as sv', 'sv.id', '=', 'ete.id_extra')
        ->where('id_evento', '=', $id)
        ->select('ete.costo', 'sv.valor', 'ete.id as id_ete')
        ->groupBy('ete.costo', 'sv.valor', 'id_ete')
        ->get();
        $cuentas = DB::table('cuentas_contables as cc')
        ->get();
        $evento=Eventos::findOrFail($id);
        $cliente=Clientes::findOrFail($evento->id_cliente);

        return view('carritos.ingresos.create', ["eventos_detalle"=>$eventos_detalle, "cliente"=>$cliente,
                                                 "cuentas"=>$cuentas, "id"=>$id, "eci"=>$eci, "extras"=>$extras]);

      }

      public function store(Request $request){
        DB::beginTransaction();
        try{
          $id_evento_ = $request->get('id_evento_');
          $evento_tmp=Eventos::findOrFail($id_evento_);
          $evento_tmp->aprobado = 2;
          $id_tercero = $request->get('id_cliente_');
          $tipo_documento = $request->get('tipo_doc');
          $numero_documento = $request->get('num_doc');
          $fecha_documento = $request->get('fecha_doc');
          $monto_neto = $request->get('valor_neto');
          $iva = $request->get('valor_iva');
          $total = $request->get('valor_total');

          $id_cuenta = $request->get('id_cuenta');
          $debe_cuenta = $request->get('debe_cuenta');
          $haber_cuenta = $request->get('haber_cuenta');
          $glosa_cuenta = $request->get('glosa_cuenta');

          $id_cuenta_c = $request->get('id_cuenta_c');
          $debe_cuenta_c = $request->get('debe_cuenta_c');
          $haber_cuenta_c = $request->get('haber_cuenta_c');
          $glosa_cuenta_c = $request->get('glosa_cuenta_c');

          $fact_temp = new Documento_financiero;
          $fact_temp->id_tercero = $id_tercero;
          $fact_temp->tipo_tercero = 'cliente';
          $fact_temp->tipo_dato = 'venta';
          $fact_temp->tipo_documento = $tipo_documento;
          $fact_temp->numero_documento = $numero_documento;
          $fact_temp->fecha_documento = $fecha_documento;
          $fact_temp->monto_neto = $monto_neto;
          $fact_temp->iva = $iva;
          $fact_temp->total = $total;
          $fact_temp->save();
          $e_t_d = new Eventos_tienen_documentos;
          $e_t_d->id_documento = $fact_temp->id;
          $e_t_d->id_evento = $id_evento_;
          $e_t_d->save();
          $evento_tmp->save();
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
          $cont = 0;
          while($cont < count($id_cuenta_c)){

            $cmf_temp = new Cuentas_movimientos;
            $cmf_temp->id_cuenta = $id_cuenta_c[$cont];
            $cmf_temp->id_documento = $fact_temp->id;
            if($debe_cuenta_c[$cont] != ''){
                  $cmf_temp->debe =  $debe_cuenta_c[$cont];
            }else{
                  $cmf_temp->debe = 0;
            }
            if($haber_cuenta_c[$cont] != ''){
                  $cmf_temp->haber = $haber_cuenta_c[$cont];
            }else{
                  $cmf_temp->haber = 0;
            }
            if($glosa_cuenta_c[$cont] != ''){
                  $cmf_temp->glosa = $glosa_cuenta_c[$cont];
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
        return Redirect::to("carritos/ingresos");

      }

}
