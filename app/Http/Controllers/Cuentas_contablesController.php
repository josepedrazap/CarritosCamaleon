<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\EventosFormRequest;
use CamaleonERP\Cuentas_contables;

use DB;

use Carbon\Carbon;


class Cuentas_contablesController extends Controller
{
    public function index(Request $request){

      //$query=trim($request->get('searchText'));
      $data=DB::table('cuentas_contables as cc')
      ->orderBy('cc.num_prefijo_abs','asc')
      ->get();

      $prefijos_0=DB::table('prefijos_cuentas as pc')
      ->where('pc.tipo', '=', 0)
      ->get();

      $prefijos_1=DB::table('prefijos_cuentas as pc')
      ->where('pc.tipo', '=', 1)
      ->get();

      $prefijos_2=DB::table('prefijos_cuentas as pc')
      ->where('pc.tipo', '=', 2)
      ->get();

      return view('carritos.cuentas_contables.index',["data"=>$data, "prefijos_1"=>$prefijos_1,
                                                      "prefijos_2"=>$prefijos_2, "prefijos_0"=>$prefijos_0]);
    }
    public function create(){
      $tipos=DB::table('selects_valores as sv')
      ->where('sv.familia', '=', 'tipo_cuenta_contable')
      ->get();
      $prefijos=DB::table('prefijos_cuentas')
      ->where('tipo', '=', 0)
      ->get();
      return view('carritos.cuentas_contables.create', ["tipos"=>$tipos, "prefijos"=>$prefijos]);
    }

    public function balance (){
        $data = DB::table('cuentas_contables as cc')
        ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
        ->select('nombre_cuenta', 'tipo', DB::raw('sum(debe) as debe'), DB::raw('sum(haber) as haber'))
        ->groupBy('nombre_cuenta', 'tipo')
        ->get();

        $total_debe = DB::table('cuentas_contables as cc')
        ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
        ->sum('debe');
        $total_haber = DB::table('cuentas_contables as cc')
        ->join('cuentas_movimientos as cm', 'cm.id_cuenta', '=', 'cc.id')
        ->sum('haber');

        return view('carritos.cuentas_contables.balance', ['data'=>$data, 'total_debe'=>$total_debe,
                                                           'total_haber'=>$total_haber]);

    }
    public function store(Request $request){

      try{
        $nombre = $request->get('nombre');
        $prefijo = $request->get('prefijo');
        $tipo =  $request->get('tipo');
        $glosa = $request->get('glosa');

        if($request->get('check')){
          $aux = 1;
          $rut = $request->get('rut');
          $tipo_doc = $request->get('tipo_documento');
        }else{
          $aux = 0;
          $rut = 0;
          $tipo_doc = 0;
        }

        $pref_ultimo = DB::table('Cuentas_contables as cc')
        ->where('prefijo', '=', $prefijo)
        ->orderBy('created_at', 'desc')
        ->take(1)
        ->get();

        if(count($pref_ultimo) > 0){
            $num_pref_f = $pref_ultimo[0]->num_prefijo_f + 1;
        }else{
            $num_pref_f = 1;
        }

        $cuenta_temp = new Cuentas_contables;
        $cuenta_temp->nombre_cuenta = $nombre;
        $cuenta_temp->prefijo = $prefijo;
        $cuenta_temp->tipo = $tipo;
        $cuenta_temp->aux = 0;
        $cuenta_temp->rut = $rut;
        $cuenta_temp->tipo_documento = $tipo_doc;
        $cuenta_temp->glosa = $glosa;

        $cuenta_temp->num_prefijo_f = $num_pref_f;
        $cuenta_temp->num_prefijo_1 = $prefijo[0];
        $cuenta_temp->num_prefijo_2 = $prefijo[2];
        $cuenta_temp->num_prefijo_3 = $prefijo[4].$prefijo[5];
        $cuenta_temp->num_prefijo_abs = $prefijo[0].$prefijo[2].$prefijo[4].$prefijo[5].$num_pref_f;

        $cuenta_temp->save();


      }catch(Exception $e){
        DB::rollback();
      }
      return Redirect::to('carritos/cuentas_contables');

    }
}
