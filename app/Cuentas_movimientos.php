<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Cuentas_movimientos extends Model
{
  protected $table = 'cuentas_movimientos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_cuenta',
    'id_documento',
    'debe',
    'haber',
    'fecha',
    'glosa'
  ];

  protected $guarded = [

  ];
}
