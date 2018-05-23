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
use Maatwebsite\Excel\Facades\Excel;

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

      public function mostrar_ingresos(Request $request){

        $date_1 = $request->get('date_1');
        $date_2 = $request->get('date_2');
        $num_com = $request->get('num_com');

        if($num_com != ''){

          $data=DB::table('documento_financiero as df')
          ->where('df.tipo_dato', '=', 'venta')
          ->join('clientes as cli', 'cli.id', '=', 'df.id_tercero')
          ->where('numero_comprobante', '=', $num_com)
          ->select('numero_documento', 'cli.nombre','df.id as id_doc', 'fecha_ingreso', 'numero_comprobante','fecha_documento','tipo_documento','cli.rut', 'nombre', 'apellido','monto_neto', 'iva', 'total')
          ->groupBy('numero_documento', 'cli.nombre','id_doc', 'fecha_ingreso','numero_comprobante','fecha_documento','tipo_documento','cli.rut', 'nombre','apellido','monto_neto', 'iva', 'total')
          ->orderBy('id_doc', 'asc')
          ->get();
        }else{
          $data=DB::table('documento_financiero as df')
          ->where('df.tipo_dato', '=', 'venta')
          ->join('clientes as cli', 'cli.id', '=', 'df.id_tercero')
          ->whereBetween('fecha_ingreso', array($date_1, $date_2))
          ->select('numero_documento', 'cli.nombre','df.id as id_doc', 'fecha_ingreso', 'numero_comprobante','fecha_documento','tipo_documento','cli.rut', 'nombre', 'apellido','monto_neto', 'iva', 'total')
          ->groupBy('numero_documento', 'cli.nombre','id_doc', 'fecha_ingreso','numero_comprobante','fecha_documento','tipo_documento','cli.rut', 'nombre','apellido','monto_neto', 'iva', 'total')
          ->orderBy('id_doc', 'asc')
          ->get();
        }



        return view('carritos.ingresos.mostrar_ingresos', ["data"=>$data, "num_com"=>$num_com,"date_1"=>$date_1, "date_2"=>$date_2]);
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
        $serie = DB::table('documento_financiero as df')
        ->max('df.numero_comprobante');

        return view('carritos.ingresos.create', ["eventos_detalle"=>$eventos_detalle, "cliente"=>$cliente, "serie"=>$serie,
                                                 "cuentas"=>$cuentas, "id"=>$id, "eci"=>$eci, "extras"=>$extras]);

      }

      public function guardar(Request $request){
          DB::beginTransaction();

          try{
            $id_tercero = $request->get('cliente');
            $tipo_documento = $request->get('tipo_doc');
            $numero_documento = $request->get('num_doc');
            $fecha_documento = $request->get('fecha_doc');
            $monto_neto = $request->get('valor_neto');
            $iva = $request->get('valor_iva');
            $total = $request->get('valor_total');
            $fecha_ingreso = $request->get('fecha_doc');
            $excento = $request->get('excento');

            $id_cuenta = $request->get('id_cuenta');
            $debe_cuenta = $request->get('debe_cuenta');
            $haber_cuenta = $request->get('haber_cuenta');
            $glosa_cuenta = $request->get('glosa_cuenta');

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
            $num_ = $request->get('numero_comprobante');
            $fact_temp->numero_comprobante = $num_;
            $fact_temp->excento = $excento;
            $fact_temp->fecha_ingreso = $fecha_ingreso;
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
          return Redirect::to("carritos/ingresos");
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
          $fecha_ingreso = $request->get('fecha_doc');
          $excento = $request->get('excento');

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
          $num_ = $request->get('numero_comprobante');
          $fact_temp->numero_comprobante = $num_;
          $fact_temp->excento = $excento;
          $fact_temp->fecha_ingreso = $fecha_ingreso;
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
            $cmf_temp->fecha = $fecha_ingreso;

            $cmf_temp->save();
            $cont++;
          }
          $cont = 0;

          if($request->get('id_cuenta_c')){
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
          }
          DB::commit();
        }catch(Exception $e){
          DB::rollback();
        }
        return Redirect::to("carritos/ingresos");

      }
      public function crear (){
        $cuentas = DB::table('cuentas_contables as cc')
        ->get();
        $clientes = Clientes::all();
        $serie = DB::table('documento_financiero as df')
        ->max('df.numero_comprobante');
        return View('carritos.ingresos.create_enduro', ["serie"=>$serie, "clientes"=>$clientes,"cuentas"=>$cuentas]);
      }

      public function index_excel ($date_1, $date_2){

        $data=DB::table('documento_financiero as df')
        ->where('df.tipo_dato', '=', 'venta')
        ->join('clientes as cli', 'cli.id', '=', 'df.id_tercero')

        ->whereBetween('fecha_ingreso', array($date_1, $date_2))
        ->select('numero_documento', 'cli.nombre','df.id as id_doc', 'fecha_ingreso', 'numero_comprobante','fecha_documento','tipo_documento','cli.rut', 'nombre', 'apellido','monto_neto', 'iva', 'total')
        ->groupBy('numero_documento', 'cli.nombre','id_doc', 'fecha_ingreso','numero_comprobante','fecha_documento','tipo_documento','cli.rut', 'nombre','apellido','monto_neto', 'iva', 'total')
        ->orderBy('id_doc', 'asc')
        ->get();
         $nombre = "ventas_periodo_".$date_1."_a_".$date_2;

         Excel::create($nombre, function($excel) use ($data) {

             $excel->sheet('Ventas', function($sheet) use ($data) {
                 $sheet->loadView('carritos.aux_views.3')
                 ->with('data', $data);
                 $sheet->setOrientation('landscape');
             });

         })->export('xlsx');
      }

}
