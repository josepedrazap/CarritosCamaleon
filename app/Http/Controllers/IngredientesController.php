<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

//use CamaleonERP\Http\Request;
use CamaleonERP\Ingredientes;
use CamaleonERP\Inventario;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\ProductosFormRequest;
use DB;

class IngredientesController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
      $this->middleware('admin');
    }
    public function index(Request $request){
      if($request){
        $query=trim($request->get('searchText'));
        $ingredientes=DB::table('ingredientes as ingr')
        ->where('ingr.nombre','LIKE','%'.$query.'%')
        ->where('ingr.condicion','=', 1)
        ->orderBy('ingr.nombre')
        ->paginate(7);
        return view('carritos.ingredientes.index', ["ingredientes"=>$ingredientes]);
      }
    }

    public function create(){
      return view('carritos.ingredientes.create');
    }
    public function cambiar_precio(Request $request){
      $id = $request->get('id');
      $ing_tmp = Ingredientes::findOrFail($id);
      $ing_tmp->precio_bruto = $request->get('precio_bruto');
      $ing_tmp->precio_liquido = $request->get('precio_liquido');
      $ing_tmp->update();
      return Redirect::to("carritos/ingredientes");

    }

    function store(Request $request){

      try{
        $ingrediente = new Ingredientes;
        $ingrediente->nombre = $request->get('nombre');
        $ingrediente->tipo = $request->get('tipo');
        $ingrediente->unidad = $request->get('unidad');
        $ingrediente->clase = "default";
        $ingrediente->condicion = 1;
        $ingrediente->precio_bruto = $request->get('precio_bruto');
        $ingrediente->precio_liquido = $request->get('precio_liquido');
        $ingrediente->iva = $request->get('iva');

        if($request->get('inventareable')){
            $ingrediente->inventareable = 1;
        }else{
            $ingrediente->inventareable = 0;
        }

        $ingrediente->save();

        $inventario = new Inventario;
        $inventario->id_item = $ingrediente->id;
        $inventario->unidad = $ingrediente->unidad;
        $inventario->cantidad = 0;
        $inventario->save();

      }catch(Exception $e){
        BD::rollback();
      }
       return Redirect::to("carritos/ingredientes");
    }


    function show($id){
      return view("carritos.ingredientes.show", ["ingrediente"=>Ingredientes::findOrFail($id)]);
    }
    function edit($id){
      return view("carritos.ingredientes.edit", ["ingrediente"=>Ingredientes::findOrFail($id), "id"=>$id]);
    }
    function update(Request $request, $id){
      $ingrediente = Ingredientes::findOrFail($id);
      $ingrediente->precio_bruto = $request->get('precio_bruto');
      $ingrediente->precio_liquido = $request->get('precio_liquido');
      $ingrediente->iva = $request->get('iva');
      $ingrediente->update();
      return Redirect::to("carritos/ingredientes");
    }
   function destroy($id){
     $ingrediente=Ingredientes::findOrFail($id);
     $ingrediente->condicion = 0;
     $ingrediente->update();

    return Redirect::to("carritos/ingredientes");

    }
}
