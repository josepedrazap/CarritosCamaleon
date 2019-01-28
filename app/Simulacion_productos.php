<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulacion_productos extends Model
{
  protected $table = 'Simulacion_productos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_producto',
    'cantidad',
    'precio_neto_unidad',
    'id_simulacion',
    'costo_neto'
  ];

  protected $guarded = [

  ];
}
