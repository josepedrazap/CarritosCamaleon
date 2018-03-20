<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use CamaleonERP\Inventario_salida;
use CamaleonERP\Eventos;
use CamaleonERP\Eventos_tienen_productos;
use CamaleonERP\Eventos_tienen_trabajadores;
use CamaleonERP\Eventos_tienen_extras;
use CamaleonERP\Eventos_costo_ingredientes;
use CamaleonERP\Eventos_detalle;

use Illuminate\Support\Facades\Input;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\EventosFormRequest;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class Utilidad_costosController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
      $this->middleware('admin');
    }
    public function index(Request $request){
      if($request){
        $query=trim($request->get('searchText'));
        $eventos=DB::table('eventos')
        ->join('eventos_detalle as ed', 'ed.id_evento', '=', 'eventos.id')
        ->join('eventos_tienen_productos as etp', 'etp.id_evento', '=', 'eventos.id')
        //->join('eventos_tienen_extras as ete', 'ete.id_evento', '=', 'eventos.id')
        ->join('productos as prod', 'prod.id', '=', 'etp.id_producto')
        ->whereIn('eventos.condicion', array(2, 3))
        ->where('eventos.aprobado', '=', 0)
        ->orderBy('eventos.id','desc')
        ->select('eventos.id', 'etp.cantidad', 'etp.precio_a_cobrar','eventos.nombre_cliente', 'eventos.condicion', 'eventos.nombre_cliente', 'eventos.fecha_hora', 'eventos.direccion', 'ed.precio_evento', 'prod.nombre')
        ->groupBy('eventos.id', 'etp.cantidad','etp.precio_a_cobrar', 'eventos.nombre_cliente', 'eventos.condicion', 'eventos.nombre_cliente', 'eventos.fecha_hora', 'eventos.direccion', 'ed.precio_evento', 'prod.nombre')
        ->paginate(7);
        return view('carritos.utilidad_costos.index',["eventos"=>$eventos, "searchText"=>$query, "id"=>0]);
      }
    }

    public function index_2(Request $request){
      if($request){
        $query=trim($request->get('searchText'));
        $eventos=DB::table('eventos')
        ->join('eventos_detalle as ed', 'ed.id_evento', '=', 'eventos.id')
        ->join('eventos_tienen_productos as etp', 'etp.id_evento', '=', 'eventos.id')
        ->join('productos as prod', 'prod.id', '=', 'etp.id_producto')
        ->whereIn('eventos.condicion', array(2, 3))
        ->where('eventos.aprobado', '=', 1)
        ->orderBy('eventos.id','desc')
        ->select('eventos.id', 'ed.costo_final','etp.cantidad', 'etp.precio_a_cobrar','eventos.nombre_cliente', 'eventos.condicion', 'eventos.nombre_cliente', 'eventos.fecha_hora', 'eventos.direccion', 'ed.precio_evento', 'prod.nombre')
        ->groupBy('eventos.id', 'ed.costo_final','etp.cantidad','etp.precio_a_cobrar', 'eventos.nombre_cliente', 'eventos.condicion', 'eventos.nombre_cliente', 'eventos.fecha_hora', 'eventos.direccion', 'ed.precio_evento', 'prod.nombre')
        ->paginate(7);
        return view('carritos.utilidad_costos.index_2',["eventos"=>$eventos, "searchText"=>$query, "id"=>0]);
      }
    }
    public function show($id){
    }

    public function ingresos(Request $request){
      $mes = $request->get('mes');
      $año = $request->get('año');

      if($año == 0){
        return view('carritos.gastos.error');
      }else if($mes == 0){
        $date_1 = Carbon::create($año, 1, 1);
        $date_2 = Carbon::create($año, 1, 1);
        $date_2->addYear(1);
        $date_1 = $date_1->format('Y-m-d');
        $date_2 = $date_2->format('Y-m-d');
      }else{
        $date_1 = Carbon::create($año, $mes, 1);
        $date_2 = Carbon::create($año, $mes, 1);
        $date_2->addMonth();
        $date_1 = $date_1->format('Y-m-d');
        $date_2 = $date_2->format('Y-m-d');
      }

      $eventos_detalle=DB::table('eventos_detalle as ed')
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->paginate(7);

      $ingreso_eventos = DB::table('eventos_detalle as ed') //suma ingreso eventos
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->sum('precio_evento');

      $costo_eventos = DB::table('eventos_detalle as ed') //suma ingreso eventos
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->sum('costo_final');

      $utilidad_eventos = DB::table('eventos_detalle as ed') //suma ingreso eventos
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->sum('utilidad_final');

      $iva_eventos_pp = DB::table('eventos_detalle as ed') //suma ingreso eventos
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->sum('iva_por_pagar');

      $iva_eventos_pc = DB::table('eventos_detalle as ed') //suma ingreso eventos
      ->join('eventos as eve', 'eve.id', '=', 'ed.id_evento')
      ->where('eve.aprobado', '=', 1)
      ->whereIn('eve.condicion', [2,3])
      ->whereBetween('fecha_hora', array($date_1, $date_2))
      ->sum('total_ingredientes_iva');

      $iva_eventos = $iva_eventos_pp - $iva_eventos_pc;

      return view('carritos.utilidad_costos.ingresos', ["data"=>$eventos_detalle, "ingreso_eventos"=>$ingreso_eventos,
                                                        "costo_eventos"=>$costo_eventos, "utilidad_eventos"=>$utilidad_eventos,
                                                        "iva_eventos"=>$iva_eventos]);
    }

    public function aprobar($id){

      $cuentas = DB::table('cuentas_contables')
      ->get();

      $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_evento', '=', $id)
      ->select('ingr.nombre as nombre', 'ingr.precio_bruto','ingr.inventareable' ,'pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre', 'ingr.inventareable', 'ingr.precio_bruto','unidad', 'id_ingr', 'stock', 'uni_inv')
      ->get();

      $extras=DB::table('eventos_tienen_extras as ete')
      ->join('selects_valores as sv', 'sv.id', '=', 'ete.id_extra')
      ->where('id_evento', '=', $id)
      ->select('ete.precio', 'sv.valor', 'ete.id as id_ete')
      ->groupBy('ete.precio', 'sv.valor', 'id_ete')
      ->get();

      $ingredientes_num=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_evento', '=', $id)
      ->count('etp.id');

      $extras_num=DB::table('eventos_tienen_extras as ext')
      ->where('id_evento', '=', $id)
      ->count('id_evento');

      $eventos_detalle=DB::table('eventos_detalle as ed')
      ->where('ed.id_evento', '=', $id)
      ->get();

      return view('carritos.utilidad_costos.create', ["id"=>$id, "eventos_detalle"=>$eventos_detalle,
                                                    "ingredientes"=>$ingredientes, "ingredientes_num"=>$ingredientes_num,
                                                    "extras"=>$extras, "extras_num"=>$extras_num, "cuentas"=>$cuentas]);
    }

    public function aprobar_2($id){

      $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->join('eventos_costo_ingredientes as ect', 'ect.id_ingrediente', '=', 'ingr.id')
      ->where('ect.id_evento', '=', $id)
      ->where('etp.id_evento', '=', $id)
      ->select('ingr.nombre as nombre', 'ect.precio_bruto as precio_bruto', 'ingr.inventareable','pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre', 'ingr.inventareable', 'precio_bruto', 'unidad', 'id_ingr', 'stock', 'uni_inv')
      ->get();

      $ingredientes_num=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->where('etp.id_evento', '=', $id)
      ->count('etp.id');

      $extras_num=DB::table('eventos_tienen_extras as ext')
      ->where('id_evento', '=', $id)
      ->count('id_evento');

      $eventos_detalle=DB::table('eventos_detalle as ed')
      ->where('ed.id_evento', '=', $id)
      ->get();

      $extras=DB::table('eventos_tienen_extras as ete')
      ->join('selects_valores as sv', 'sv.id', '=', 'ete.id_extra')
      ->where('id_evento', '=', $id)
      ->select('ete.precio', 'sv.valor', 'ete.id as id_ete', 'costo')
      ->groupBy('ete.precio', 'sv.valor', 'id_ete', 'costo')
      ->get();


      return view('carritos.utilidad_costos.create_2', ["id"=>$id, "eventos_detalle"=>$eventos_detalle,
                                                    "ingredientes"=>$ingredientes, "extras_num"=>$extras_num,
                                                    "ingredientes_num"=>$ingredientes_num, "extras"=>$extras,
                                                    ]);
    }

    public function store(Request $request){

      try{
        $costos = $request->get('costo_ingr');
        $id_ingr = $request->get('id_ingr');
        $costo_ingr_total = $request->get('costo_ingr_total');
        $precio_bruto_ingr = $request->get('precio_bruto_ingr');
        $costo_final_evento = $request->get('costo_final_evento');
        $iva_ingr = $request->get('IVA_ingredientes');
        $utilidad_final = $request->get('Utilidad_final');
        $costo_extras_aux = $request->get('costo_extras');

        $id_ext = $request->get('id_ext');
        $costo_ext = $request->get('costo_ext');

        $evento_temp=Eventos::findOrFail($request->id_evento_);
        $evento_temp->aprobado = 1;
        $evento_temp->save();

        $eventos_detalle=DB::table('eventos_detalle as ed')
        ->where('ed.id_evento', '=', $request->id_evento_)
        ->get();

        $id = $eventos_detalle[0]->id;

        $evento_detalle=Eventos_detalle::findOrFail($id);
        $evento_detalle->total_ingredientes = intval($costo_ingr_total);
        $evento_detalle->total_ingredientes_iva = intval($costo_ingr_total*0.19);
        $evento_detalle->utilidad_final = intval($utilidad_final);
        $evento_detalle->costo_final = intval($costo_final_evento);
        $evento_detalle->gasto_extra = $costo_extras_aux;
        $evento_detalle->update();

        $cont = 0;
        while($cont < count($costos)){
          $eve_c_ingr = new Eventos_costo_ingredientes;
          $eve_c_ingr->id_ingrediente = $id_ingr[$cont];
          $eve_c_ingr->id_evento = $request->id_evento_;
          $eve_c_ingr->costo_total = $costos[$cont];
          $eve_c_ingr->precio_bruto = $precio_bruto_ingr[$cont];
          $eve_c_ingr->save();
          $cont++;
        }
        $cont = 0;
      if( $request->get('costo_ext')){
        while($cont < count($costo_ext)){
          $id_extra = $id_ext[$cont];
          $extra_temp = Eventos_tienen_extras::findOrFail($id_extra);
          $extra_temp->costo = $costo_ext[$cont];
          $extra_temp->update();
          $cont++;
        }
      }
      }catch(Exception $e){
        DB::rollback();
      }


      return Redirect::to("carritos/utilidad_costos");
    }
}
