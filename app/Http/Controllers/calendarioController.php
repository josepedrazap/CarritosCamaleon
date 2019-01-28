<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use Calendar;
use DB;
use Carbon\Carbon;


class calendarioController extends Controller
{
    public function index_eventos()
    {
       $events = [];
       $data =DB::table('eventos_2')
         ->whereIn('condicion', array(1,2,3))
         ->orderBy('fecha_hora','desc')
         ->get();
       if(count($data)){
          foreach ($data as $key => $value) {

            $url = "";

            if($value->condicion == 2){
                $url = "/ver_evento?id=" . $value->id;
            }
            if($value->condicion == 1){
                $url = "/ver_evento?id=" . $value->id;
            }

            $event = Calendar::event(
                $value->cliente,
                true,
                new \DateTime($value->fecha_hora),
                new \DateTime($value->fecha_hora.' +1 day'),
                0,
                [
		                 'url' => $url
	              ]
            );
            if($value->condicion == 1){
                $calendar = Calendar::addEvent($event, ['color' => '#E67E22']);
            }
            if($value->condicion == 2){
                $calendar = Calendar::addEvent($event, ['color' => '#2ECC71']);
            }
            if($value->condicion == 3){
                $calendar = Calendar::addEvent($event, ['color' => '#BDC3C7']);
            }

          }
       }


      return view('carritos.calendario.index', compact('calendar'), ["tit" => "Calendario de eventos"]);

    }

    public function index_cotizaciones()
    {
       $events = [];
       $data =DB::table('eventos')
         ->where('condicion', '=', 6)
         ->orderBy('fecha_hora','desc')
         ->get();
       if(count($data)){
          foreach ($data as $key => $value) {

            $url = "/carritos/eventos/" . $value->id;

            $event = Calendar::event(
                $value->nombre_cliente,
                true,
                new \DateTime($value->fecha_hora),
                new \DateTime($value->fecha_hora.' +1 day'),
                0,
                [
		                 'url' => $url
	              ]
            );
            $calendar = Calendar::addEvent($event, ['color' => '#3498DB']);
          }
       }
      return view('carritos.calendario.index', compact('calendar'), ["tit" => "Calendario de cotizaciones"]);

    }

    public function index_financiero()
    {
       $events = [];

       $data = DB::table('documento_financiero')
         ->get();

       if(count($data)){
          foreach ($data as $key => $value) {

            $url = '';

            if($value->tipo_dato == 'compra'){
              $url = "/carritos/compras/" . $value->id;
            }
            if($value->tipo_dato == 'venta'){
            }
            if($value->tipo_dato == 'gasto'){
            }
            if($value->tipo_dato == 'honorario'){
            }

            $nombre = $value->tipo_dato . " " . $value->numero_comprobante;
            $event = Calendar::event(
                $nombre,
                true,
                new \DateTime($value->fecha_ingreso),
                new \DateTime($value->fecha_ingreso.' +1 day'),
                0,
                [
		                 'url' => $url
	              ]
            );
            if($value->tipo_dato == 'compra'){
              $calendar = Calendar::addEvent($event, ['color' => '#A9CCE3']);
            }
            if($value->tipo_dato == 'venta'){
              $calendar = Calendar::addEvent($event, ['color' => '#A9DFBF']);
            }
            if($value->tipo_dato == 'gasto'){
              $calendar = Calendar::addEvent($event, ['color' => '#F1948A']);
            }
            if($value->tipo_dato == 'honorario'){
              $calendar = Calendar::addEvent($event, ['color' => '#F7DC6F']);
            }

          }
       }
      return view('carritos.calendario.index', compact('calendar'), ["tit" => "Calendario financiero"]);

    }

    public function calendario(){
        $data =DB::table('simulaciones as s')
        ->where("estado", "=", 1)
        ->select('s.nombre as title', 's.id as id','s.fecha as start', 's.estado as estado')
        ->orderBy('fecha','desc')
        ->get();

        $cont = 0;
        while($cont < count($data)){
          if($data[$cont]->estado == 0){
            $data[$cont]->color = 'red';
          }else if($data[$cont]->estado == 1){
            $data[$cont]->color = 'sky-blue';
          }else{
            $data[$cont]->color = 'orange';
          }
          $cont++;
        }

      //return $data;
      return view('carritos.calendario.calendario', ["data"=>$data]);
    }

    public function eventos(){
      $data =DB::table('eventos')
        ->where('condicion', '=', 6)
        ->select('eventos.nombre_cliente as title', 'eventos.fecha_hora as start')
        ->orderBy('fecha_hora','desc')
        ->get();

        $clientes = DB::table('clientes')
        ->get();

        $cont = 0;
        $clientes_;
        while($cont < count($clientes)){
          $clientes_[$clientes[$cont]->id] = $clientes[$cont]->nombre;
          $cont++;
        }
        return $clientes_;
    }


}
