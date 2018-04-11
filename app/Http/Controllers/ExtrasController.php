<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;


//use CamaleonERP\Http\Request;
use CamaleonERP\Selects_valores;

class ExtrasController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(){

      $extras=DB::table('selects_valores as ext')
      ->where('ext.familia','=','extras')
      ->where('ext.condicion','=', 1)
      ->orderBy('ext.valor')
      ->get();
      return view('carritos.extras.index', ["extras"=>$extras]);

  }
  public function create(){
      return view('carritos.extras.create');
  }
  public function store(Request $request){

    $extra_tmp = new Selects_valores;
    $extra_tmp->valor = $request->get('extra');
    $extra_tmp->familia = "extras";
    $extra_tmp->condicion = 1;
    $extra_tmp->save();
    return Redirect::to("carritos/extras");

  }

public function destroy($id){
  $extra_tmp = Selects_valores::findOrFail($id);
  $extra_tmp->condicion = 0;
  $extra_tmp->update();
  return Redirect::to("carritos/extras");

}
}
