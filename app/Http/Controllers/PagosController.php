<?php


namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\Trabajador_detalle;
use CamaleonERP\Trabajadores;
use CamaleonERP\Eventos_tienen_trabajadores;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\TrabajadoresFormRequest;
use DB;

class PagosController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }
  public function index(Request $request){

    if($request){
      $query=trim($request->get('searchText'));
      $data=DB::table('trabajadores as tra')
      ->join('trabajador_detalle as td', 'td.id_trabajador', '=', 'tra.id')
      ->join('eventos_tienen_trabajadores as ett', 'ett.id_trabajador', '=', 'tra.id')
      ->where('nombre','LIKE','%'.$query.'%')
      ->where('ett.estado','=',1)
      ->select('tra.id', 'tra.nombre as nombre', 'tra.apellido as apellido', 'tra.clase as clase', DB::raw('sum(monto) as sum'),  DB::raw('count(monto) as cont'))
      ->groupBy('tra.id', 'nombre', 'apellido', 'clase')
      ->paginate(7);

      return view('carritos.pagos.index', ["data"=>$data]);

    }
  }

  public function create(){
    return view('carritos.pagos.create');
  }

  function store(Request $request){
    DB::beginTransaction();
    try{
      $id_pago = $request->get('id_pago');
      $cont = 0;
      while($cont < count($id_pago)){
        $id = $id_pago[$cont];
        if($request->get($id)){
          $ett=Eventos_tienen_trabajadores::findOrFail($id);
          $ett->estado = 2;
          $ett->update();
        }
          $cont++;
      }
      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }

    return Redirect::to("carritos/pagos");
  }

  public function vertodos($id){
    $data=DB::table('eventos as eve')
    ->join('eventos_tienen_trabajadores as ett', 'ett.id_evento', '=', 'eve.id')
    ->join('trabajadores as tra', 'tra.id', '=', 'ett.id_trabajador')
    ->join('trabajador_detalle as td', 'tra.id', '=', 'td.id_trabajador')
    ->where('tra.id','=',$id)
    ->select('tra.nombre as nombre', 'ett.estado','td.cuenta', 'td.rut', 'td.tipo_cuenta', 'td.banco','eve.condicion','tra.apellido as apellido', 'eve.fecha_hora','eve.nombre_cliente as nombre_cliente', 'ett.monto as monto', 'ett.id')
    ->groupBy('nombre', 'apellido', 'td.cuenta', 'ett.estado', 'td.tipo_cuenta', 'td.rut', 'td.banco', 'eve.condicion','monto', 'nombre_cliente', 'eve.fecha_hora', 'ett.id')
    ->get();

    $cont=DB::table('eventos_tienen_trabajadores as ett')
    ->where('ett.id_trabajador', '=', $id)
    ->count('monto');

    $sum=DB::table('eventos_tienen_trabajadores as ett')
    ->where('ett.id_trabajador', '=', $id)
    ->where('ett.estado', '=', '2')
    ->sum('monto');


    $sum_=DB::table('eventos_tienen_trabajadores as ett')
    ->where('ett.id_trabajador', '=', $id)
    ->where('ett.estado', '=', '1')
    ->sum('monto');

    return view('carritos.pagos.vertodos', ["data"=>$data, "id_t"=>$id, "cont"=>$cont, "sum"=>$sum, "sum_"=>$sum_]);
  }

  public function pagar($id){
    $data=DB::table('eventos as eve')
    ->join('eventos_tienen_trabajadores as ett', 'ett.id_evento', '=', 'eve.id')
    ->join('trabajadores as tra', 'tra.id', '=', 'ett.id_trabajador')
    ->join('trabajador_detalle as td', 'tra.id', '=', 'td.id_trabajador')
    ->where('tra.id','=',$id)
    ->where('ett.estado', '=', '1')
    ->select('tra.nombre as nombre', 'td.cuenta', 'td.rut', 'td.tipo_cuenta', 'td.banco','eve.condicion','tra.apellido as apellido', 'eve.fecha_hora','eve.nombre_cliente as nombre_cliente', 'ett.monto as monto', 'ett.id')
    ->groupBy('nombre', 'apellido', 'td.cuenta', 'td.tipo_cuenta', 'td.rut', 'td.banco', 'eve.condicion','monto', 'nombre_cliente', 'eve.fecha_hora', 'ett.id')
    ->get();
    return view('carritos.pagos.create', ["data"=>$data, "id_t"=>$id]);
  }

  function show($id){
    //return view("carritos.trabajadores.show", ["producto"=>Productos::findOrFail($id)]);
  }
  function edit($id){
    //return view("carritos.trabajadores.edit", ["producto"=>Productos::findOrFail($id)]);
  }
  function update(TrabajadoresFormRequest $request, $id){
    return Redirect::to("carritos/pagos");
  }

 function destroy($id){
   $trabajador=Trabajadores::findOrFail($id);
   $trabajador->condicion = 0;
   $trabajador->update();

   return Redirect::to("carritos/trabajadores");

  }
}
