<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\Trabajador_detalle;
use CamaleonERP\Clientes;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\TrabajadoresFormRequest;
use DB;

class ClientesController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function index(Request $request){

    if($request){
      $query=trim($request->get('searchText'));
      $clientes=DB::table('clientes as cli')
      //->join('eventos as eve', 'eve.id_cliente', '=', 'cli.id')
      ->where('cli.nombre','LIKE','%'.$query.'%')
      ->orwhere('cli.apellido','LIKE','%'.$query.'%')
      ->select('cli.id','cli.nombre', 'cli.apellido', 'cli.contacto', 'cli.mail')
      ->groupBy('cli.id', 'cli.nombre', 'cli.apellido', 'cli.contacto', 'cli.mail')
      ->orderBy('cli.id','desc')
      ->paginate(7);

      return view('carritos.clientes.index', ["clientes"=>$clientes]);
    }
  }
  public function create(){
    return view('carritos.clientes.create');
  }
  public function store(Request $request){
    try{
        $cliente = new Clientes;
        $cliente->nombre = $request->get('nombre');
        $cliente->apellido = $request->get('apellido');
        $cliente->mail = $request->get('email');
        $cliente->contacto = $request->get('contacto');
        $cliente->save();
    }catch(Exception $e){
        DB::rollback();
    }
    return Redirect::to("carritos/clientes");

  }
}
