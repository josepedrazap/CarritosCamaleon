<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;

use CamaleonERP\User;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\UserFormRequest;
use DB;


class AuxController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('admin');
  }

    function registrar(){
      return view('Auth.register');
    }
    public function index(){
      $data=DB::table('users')->get();
      return view('carritos.aux_views.index', ["data"=>$data]);
    }

    function store(UserFormRequest $request){

      try{
        $user = new User;
        $user->name = $request->get('name');
        $user->password = bcrypt($request->get('password'));
        $user->email = $request->get('email');
        $user->nivel = $request->get('nivel');
        $user->save();
        return view('carritos.aux_views.succes', ["resultado" => 1, "user"=>$user]);
      }catch(Exception $e){
        return view('carritos.aux_views.succes', ["resultado" => 0]);
      }

    }
}
