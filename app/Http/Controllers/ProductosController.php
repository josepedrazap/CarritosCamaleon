<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

//use CamaleonERP\Http\Request;
use CamaleonERP\Productos;
use CamaleonERP\Productos_tienen_ingredientes;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\ProductosFormRequest;
use DB;

class ProductosController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
      $this->middleware('admin');
    }
    public function index(Request $request){
      if($request){
        $id_ = 0;
        $query=trim($request->get('searchText'));
        $productos=DB::table('productos as p')->where('p.nombre','LIKE','%'.$query.'%')
        ->join('productos_tienen_ingredientes as pti', 'p.id', '=', 'pti.id_producto')
        ->join('ingredientes as ingr', 'ingr.id', '=', 'pti.id_ingrediente')
        ->select('p.nombre','p.id', 'p.precio', 'pti.porcion as porcion', 'pti.unidad as unidad', 'ingr.nombre as ingrediente')
        ->orderBy('p.id')
        ->groupBy('p.nombre','p.id', 'p.precio', 'pti.porcion', 'pti.unidad', 'ingr.nombre')
        ->where('p.condicion', '=', '1')
        ->get();
        return view('carritos.productos.index', ["productos"=>$productos, "id"=>$id_]);
      }
    }

    public function create(){

      $ingredientes=DB::table('ingredientes as ingr')
      ->where('ingr.condicion', '=', '1')
      ->orderBy('ingr.tipo', 'desc')
      ->get();

      $tipos = DB::table('selects_valores as sv')
      ->where('sv.familia', '=', 'tipo_productos')
      ->get();

      $bases = DB::table('selects_valores as sv')
      ->where('sv.familia', '=', 'base_productos')
      ->get();

      $ayudas = DB::table('ayudas')
      ->where('ayudas.familia', '=', 'productos_create')
      ->get();


      return view('carritos.productos.create', ["ayudas"=>$ayudas, "ingredientes"=>$ingredientes, "tipos"=>$tipos, "bases"=>$bases]);
    }

    function store(ProductosFormRequest $request){
      DB::beginTransaction();
      try{
        $producto = new Productos;
        $producto->nombre = $request->get('nombre_producto');
        $producto->precio = $request->get('precio');
        $producto->tipo = $request->get('tipo');
        $producto->base = $request->get('plataforma');
        $producto->condicion = 1;
        $producto->save();

        $ingrs_name = $request->get('ingrediente_');
        $ingrs = $request->get('cant_ingrediente_');
        $unis = $request->get('uni_');

        $i = 0;

        while($i < count($ingrs_name)){

            $prod_t_ingr = new Productos_tienen_ingredientes;
            $prod_t_ingr->id_producto = $producto->id;
            $prod_t_ingr->id_ingrediente = $ingrs_name[$i];
            $prod_t_ingr->porcion = $ingrs[$i];
            $prod_t_ingr->unidad = $unis[$i];
            $prod_t_ingr->save();
          $i++;
        }
        DB::commit();
      }catch(Exception $e){
        DB::rollback();
      }
       return Redirect::to("carritos/productos");
    }

    function addproductos(){
    }

    function show($id){
      return view("carritos.productos.show", ["producto"=>Productos::findOrFail($id)]);
    }
    function edit($id){
      return view("carritos.productos.edit", ["producto"=>Productos::findOrFail($id)]);
    }
    function update(ProductosFormRequest $request, $id){
      $producto = FindOrFail($id);
      $producto->Nombre = $request->get('Nombre');
      $producto->Cantidad = $request->get('Cantidad');
      $producto->Tipo = $request->get('Tipo');
      $producto->update();

      return Redirect::to("carritos/productos");
    }
   function destroy($id){
     $producto=Productos::findOrFail($id);
     $producto->condicion = 0;
     $producto->update();

    return Redirect::to("carritos/productos");

    }
}
