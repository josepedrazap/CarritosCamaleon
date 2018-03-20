<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Trabajador_detalle extends Model
{
  protected $table = 'trabajador_detalle';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'email',
    'telefono',
    'banco',
    'cuenta',
    'tipo_cuenta',
    'descripcion',
    'maneja',
    'id_trabajador',
    'rut'
  ];

  protected $guarded = [

  ];
}
