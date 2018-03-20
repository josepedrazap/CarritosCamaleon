<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use CamaleonERP\Inventario_salida;
use CamaleonERP\Eventos;
use CamaleonERP\Eventos_tienen_productos;
use CamaleonERP\Eventos_tienen_trabajadores;
use CamaleonERP\Eventos_tienen_extras;
use CamaleonERP\Eventos_detalle;
use CamaleonERP\Inventario;

use Illuminate\Support\Facades\Input;
use Codedge\Fpdf\Facades\Fpdf;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\EventosFormRequest;
use DB;
use Illuminate\Support\Facades\Storage;

class DespachoController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
    }

    public function index(Request $request){

        if($request){
          $query=trim($request->get('searchText'));
          $eventos=DB::table('eventos')->where('nombre_cliente','LIKE','%'.$query.'%')
          ->where('condicion', '=', 2)
          ->orderBy('id','desc')
          ->paginate(7);
          return view('carritos.despacho.index',["eventos"=>$eventos, "searchText"=>$query]);
        }
    }

    public function create(Request $request){

      $id = $request->id;

      $evento=DB::table('eventos')
      ->where('eventos.id', '=', $id)
      ->paginate(7);

      $productos=DB::table('productos as prod')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->where('etp.id_evento', '=', $id)
      ->select('prod.nombre', 'etp.cantidad as cantidad', 'precio', 'etp.id as id_etp', 'base', DB::raw('sum(cantidad) as sum'))
      ->groupBy('nombre','cantidad', 'precio', 'base', 'id_etp')
      ->get();

      $base=DB::table('productos as prod')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->where('etp.id_evento', '=', $id)
      ->select('base', DB::raw('sum(cantidad) as sum'))
      ->groupBy('base')
      ->get();

      $total=DB::table('productos as prod')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->where('etp.id_evento', '=', $id)
      ->select( 'etp.id_evento as id_e', DB::raw('sum(cantidad*precio) as sum'))
      ->groupBy('id_e')
      ->get();

      $extras=DB::table('selects_valores as sv')
      ->join('eventos_tienen_extras as ete', 'sv.id', '=', 'ete.id_extra')
      ->where('ete.id_evento', '=', $id)
      ->get();

      $ingredientes=DB::table('productos as prod')
      ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
      ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
      ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
      ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
      ->where('etp.id_evento', '=', $id)
      ->select('ingr.nombre as nombre', 'ingr.inventareable' ,'pti.unidad as unidad', 'ingr.unidad as uni_inv','inv.cantidad as stock','ingr.id as id_ingr', DB::raw('sum(porcion*etp.cantidad) as sum'))
      ->groupBy('nombre', 'ingr.inventareable' ,'unidad', 'id_ingr', 'stock', 'uni_inv')
      ->get();

      $trabajadores=DB::table('trabajadores as tra')
      ->join('trabajador_detalle as td', 'tra.id', '=', 'td.id_trabajador')
      ->where('tra.condicion', '=', 1)
      ->select('tra.nombre as nombre', 'td.maneja', 'tra.id as id', 'tra.apellido as apellido')
      ->groupBy('nombre', 'maneja', 'id', 'apellido')
      ->get();

      $num_prod=DB::table('eventos_tienen_productos as etp')
      ->where('etp.id_evento', '=', $id)
      ->count('id_producto');

      $num_ext=DB::table('eventos_tienen_extras as ete')
      ->where('ete.id_evento', '=', $id)
      ->count('id_extra');

      $i_ingr = count($ingredientes);
      return view('carritos.despacho.create', ["evento"=>$evento, "ingredientes"=>$ingredientes,
                                               "productos"=>$productos, "extras"=>$extras,
                                               "trabajadores"=>$trabajadores, "base" => $base,
                                               "total"=>$total, "num_prod" => $num_prod,
                                               "pago_cocinero"=>20000, "i_ingr" => $i_ingr, "num_ext"=>$num_ext]);
    }

    public function edit($id){
        return view('carritos.despacho.agregarproductos');
    }

    public function store(Request $request){

      try{
        DB::beginTransaction();

        $id = $request->id_evento_;
        $eventos = Eventos::findOrFail($id);

        if($eventos->condicion != 4){
          $eventos = Eventos::findOrFail($id);
          $eventos->condicion = 2;
          $eventos->update();
        }

        $i_ingr = $request->get('i_ingr');
        $trabajadores = $request->get('trabajador');

        $precio_real = $request->get('precio_real');
        $id_etps = $request->get('id_etp');

        $precio_real_ext = $request->get('precio_extra');

        $total_final = $request->get('total_final');
        $total_final_iva = $request->get('total_iva');
        $extra_movil = $request->get('extra_movil');
        $pago_cocineros = $request->get('pago_cocinero');
        $prod_iva_sum = $request->get('iva_sum');
        $prod_total_sum = $request->get('total_sum');

        $eve_det = new Eventos_detalle;
        $eve_det->id_evento = $id;
        $eve_det->gasto_extra = $extra_movil;
        $eve_det->iva_por_pagar = $total_final_iva;
        $eve_det->precio_evento = $total_final;
        $eve_det->pago_cocineros = $pago_cocineros;
        $eve_det->total_ingredientes = 0;
        $eve_det->total_ingredientes_iva = 0;
        $eve_det->utilidad_final = 0;
        $eve_det->costo_final = 0;
        $eve_det->total_productos = $prod_total_sum;
        $eve_det->total_productos_iva = $prod_iva_sum;
        $eve_det->save();

        $cont = 0;
        while($cont < count($trabajadores) && $eventos->condicion == 2){
          $trab = new Eventos_tienen_trabajadores;
          $trab->id_trabajador = $trabajadores[$cont];
          $trab->monto = 20000;
          $trab->estado = 1;
          $trab->id_evento = $id;
          $trab->save();
          $cont++;
        }

        $cont = 0;
        while($cont < count($id_etps)){
          $id_temp = $id_etps[$cont];
          $etp_temp = Eventos_tienen_productos::findOrFail($id_temp);
          $etp_temp->precio_a_cobrar = $precio_real[$cont];
          $etp_temp->update();
          $cont++;
        }

        if($request->get('id_ete')){
              $id_etes = $request->get('id_ete');
              $cont = 0;
              while($cont < count($id_etes)){
                $id_temp = $id_etes[$cont];
                $ete_temp = Eventos_tienen_extras::findOrFail($id_temp);
                $ete_temp->precio = $precio_real_ext[$cont];
                $ete_temp->update();
                $cont++;
              }
        }
        //descuento del inventario si el ingrediente es inventareable
        $ingredientes=DB::table('productos as prod')
        ->join('productos_tienen_ingredientes as pti', 'prod.id', '=', 'pti.id_producto')
        ->join('ingredientes as ingr', 'pti.id_ingrediente', '=', 'ingr.id')
        ->join('eventos_tienen_productos as etp', 'prod.id', '=', 'etp.id_producto')
        ->join('inventario as inv', 'inv.id_item', '=', 'ingr.id')
        ->where('etp.id_evento', '=', $id)
        ->where('ingr.inventareable', '=', 1) //inventareable?
        ->select('ingr.id as id_ingr', 'inv.id as id_inv', DB::raw('sum(porcion*etp.cantidad) as sum'))
        ->groupBy('id_ingr', 'id_inv')
        ->get();

        $cont = 0;
        while($cont < count($ingredientes)){
          $inv_temp = Inventario::findOrFail($ingredientes[$cont]->id_inv);
          $inv_temp->cantidad = $inv_temp->cantidad - $ingredientes[$cont]->sum;
          $inv_temp->update();
          $cont++;
        }

        DB::commit();

      }catch(Exception $e){
          DB::rollback();
      }

      if($eventos->condicion == 4){
          return Redirect::to("carritos/cotizaciones");
      }else{
          return Redirect::to("carritos/despacho");
      }
    }

    public function despachados(){
      //  $data = datos_despacho($id);
    }
}
