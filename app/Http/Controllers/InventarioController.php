<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use CamaleonERP\Productos;
use CamaleonERP\Compras;
use CamaleonERP\Inventario_entrada;
use CamaleonERP\Eventos_tienen_extras;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\InventarioFormRequest;
use DB;

class InventarioController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(Request $request){

    if($request){
      $query=trim($request->get('searchText'));
      $data=DB::table('inventario as inv')
      ->join('ingredientes as ingr', 'inv.id_item', '=', 'ingr.id')
      ->where('ingr.nombre', 'LIKE', '%'.$query.'%')
      ->where('ingr.inventareable', '=', '1')
      ->get();
      return view('carritos.inventario.index',["data"=>$data, "searchText"=>$query]);

    }
  }
  function create(){
      $items=DB::table('ingredientes')
      ->where('inventareable', '=', 1)
      ->get();
      return view('carritos.inventario.create', ["items"=>$items]);
  }
  function store(Request $request){
    DB::beginTransaction();

    try{


      $iditem = $request->get('iditem');
      $cantidaditem = $request->get('cantidaditem');
      $cont=0;
      while($cont < count($iditem)){

          $entrada = new Inventario_entrada;
          $entrada->cantidad = $cantidaditem[$cont];
          $entrada->id_item = $iditem[$cont];
          $entrada->monto = 0;
          $entrada->descripcion = "compra";
          $entrada->save();
          $cont++;
      }
      DB::commit();

    }catch(Exception $e){
      DB::rollback();
    }
    return Redirect::to('carritos/inventario');
  }
}
