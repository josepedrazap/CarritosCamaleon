<?php

namespace CamaleonERP\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use CamaleonERP\Http\Requests\UserFormRequest;
use DB;
use CamaleonERP\User;

class ResetpassController extends Controller
{
  public function create(){
    return view('carritos.aux_views.cambiar_contraseña');
  }
  public function store(UserFormRequest $request){
    DB::beginTransaction();
    try{
      $user = User::findOrFail($request->get('id'));
      $user->password = $request->get('password');
      $user->update();
      return view('carritos.aux_views.succes', ["resultado" => 2, "user"=>$user]);
      DB::commit();
    }catch(Exception $e){
      DB::rollback();
    }
  }
}
