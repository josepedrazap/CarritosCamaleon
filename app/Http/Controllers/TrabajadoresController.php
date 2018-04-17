<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\Trabajador_detalle;
use CamaleonERP\Trabajadores;


use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\TrabajadoresFormRequest;
use DB;

class TrabajadoresController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(Request $request){

    if($request){
      $query=trim($request->get('searchText'));
      $trabajadores=DB::table('trabajadores as tra')
      ->join('trabajador_detalle as td', 'td.id_trabajador', '=', 'tra.id')
      ->where('nombre','LIKE','%'.$query.'%')
      ->where('condicion','=',1)
      ->select('tra.nombre', 'tra.apellido', 'tra.condicion', 'tra.clase', 'td.email', 'td.telefono', 'maneja', 'tra.id')
      ->groupBy('tra.nombre', 'tra.apellido', 'tra.condicion', 'tra.clase', 'td.email', 'td.telefono', 'maneja', 'tra.id')
      ->orderBy('tra.id','desc')
      ->paginate(7);

      return view('carritos.trabajadores.index', ["trabajadores"=>$trabajadores]);

    }
  }
  function edit($id){
    $tra_tmp = DB::table('trabajador_detalle as td')
    ->join('trabajadores as t', 't.id', '=', 'td.id_trabajador')
    ->where('td.id_trabajador', '=', $id)
    ->get();
    $bancos=DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'BANCOS')
    ->get();
    $cuentas=DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'TIPO_CUENTA_BANCARIA')
    ->get();
    return view("carritos.trabajadores.edit", ["trabajador"=>$tra_tmp, "id"=>$id, "bancos"=>$bancos, "cuentas"=>$cuentas]);
  }

  public function editar(Request $request){
    $id = $request->get('id');
    $tra_tmp = Trabajadores::findOrFail($id);
    $tra_tmp->nombre = $request->get('nombre');
    $tra_tmp->apellido = $request->get('apellido');
    $tra_tmp->clase = $request->get('clase');
    $tra_tmp->update();

    $tra_tmp = DB::table('trabajador_detalle as td')
    ->where('td.id_trabajador', '=', $id)
    ->get();
    $tra_tmp =Trabajador_detalle::findOrFail($tra_tmp[0]->id);
    $tra_tmp->email = $request->get('email');
    $tra_tmp->telefono = $request->get('telefono');
    $tra_tmp->maneja = $request->get('maneja');
    $tra_tmp->rut = $request->get('rut');
    $tra_tmp->cuenta = $request->get('cuenta');

    if($request->get('descripcion'))$tra_tmp->descripcion = $request->get('descripcion');

    $tra_tmp->update();

    return Redirect::to("carritos/trabajadores");

  }
  public function create(){
    $bancos=DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'BANCOS')
    ->get();
    $cuentas=DB::table('selects_valores as sv')
    ->where('sv.familia', '=', 'TIPO_CUENTA_BANCARIA')
    ->get();
    return view('carritos.trabajadores.create', ["bancos"=>$bancos, "cuentas"=>$cuentas]);
  }

  function store(TrabajadoresFormRequest $request){
    DB::beginTransaction();
    try{
      $trabajador = new Trabajadores;
      $trabajador->nombre = $request->get('nombre_trabajador');
      $trabajador->apellido = $request->get('apellido_trabajador');
      $trabajador->clase = $request->get('clase');
      $trabajador->condicion = 1;
      $trabajador->save();

      $trab_detalle = new Trabajador_detalle;
      $trab_detalle->email = $request->get('email_trabajador');
      $trab_detalle->telefono = $request->get('telefono_trabajador');
      $trab_detalle->banco = $request->get('banco');
      $trab_detalle->rut = $request->get('rut');
      $trab_detalle->tipo_cuenta = $request->get('tipo_cuenta');
      $trab_detalle->cuenta = $request->get('numero_cuenta');
      $trab_detalle->maneja = $request->get('maneja');
      $trab_detalle->id_trabajador = $trabajador->id;

      if($request->get('descripcion')){
        $trab_detalle->descripcion = $request->get('descripcion');
      }else{
        $trab_detalle->descripcion = "Sin descripción.";
      }
      $trab_detalle->save();
      DB::commit();
    }catch(Exception $e){
        DB::rollback();
    }

    return Redirect::to("carritos/trabajadores");
  }

  function addproductos(){
    return view('carritos.trabajadores.agregarproductos');
  }

  function show($id){
    //return view("carritos.trabajadores.show", ["producto"=>Productos::findOrFail($id)]);
  }

  function update(TrabajadoresFormRequest $request, $id){
    //$producto = FindOrFail($id);
    //$producto->Nombre = $request->get('Nombre');
    //$producto->Cantidad = $request->get('Cantidad');
    //$producto->Tipo = $request->get('Tipo');
    //$producto->update();

    return Redirect::to("carritos/trabajadores");
  }

 function destroy($id){

   $E = "No se pueden eliminar trabajadores con pagos pendientes. Realiza los pagos pendientes y vuelve a intentarlo.";

   $sum= DB::table('eventos_tienen_trabajadores')
       ->where('id_trabajador', '=', $id)
       ->where('estado', '=', '1')
       ->count('monto');

       if($sum == 0){
         $trabajador=Trabajadores::findOrFail($id);
         $trabajador->condicion = 0;
         $trabajador->update();
         return Redirect::to("carritos/trabajadores");

       }else{
         return view('carritos.trabajadores.error', ["error"=>$E]);
       }
  }
}
