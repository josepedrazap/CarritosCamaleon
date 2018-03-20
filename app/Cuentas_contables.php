<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Cuentas_contables extends Model
{
  protected $table = 'cuentas_contables';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'nombre_cuenta',
    'prefijo',
    'tipo',
    'aux',
    'rut',
    'tipo_documento',
    'glosa',
    'num_prefijo_f',
    'num_prefijo_1',
    'num_prefijo_2',
    'num_prefijo_3',
    'num_prefijo_abs'
  ];

  protected $guarded = [

  ];
}
