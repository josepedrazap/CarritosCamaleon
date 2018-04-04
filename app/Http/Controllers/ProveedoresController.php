<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\Proveedores;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\TrabajadoresFormRequest;
use DB;

class ProveedoresController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function index(Request $request){

    if($request){
      $query=trim($request->get('searchText'));
      $proveedores=DB::table('proveedores as prov')
      ->where('prov.nombre','LIKE','%'.$query.'%')
      ->orderBy('prov.id','desc')
      ->paginate(7);

      return view('carritos.proveedores.index', ["proveedores"=>$proveedores]);
    }
  }
  public function create(){
    return view('carritos.proveedores.create');
  }
  public function store(Request $request){
    DB::beginTransaction();
    try{
        $prov_temp = new Proveedores;
        $prov_temp->nombre = $request->get('nombre');
        $prov_temp->rut = $request->get('rut');
        $prov_temp->email = $request->get('email');
        $prov_temp->telefono = $request->get('telefono');
        $prov_temp->descripcion = $request->get('descripcion');
        $prov_temp->save();
        DB::commit();
    }catch(Exception $e){
        DB::rollback();
    }
    return Redirect::to("carritos/proveedores");

  }
}
