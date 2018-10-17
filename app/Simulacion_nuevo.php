<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulacion_nuevo extends Model
{
  protected $table = 'simulacion_nuevo';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'cantidad',
    'costo_neto_unidad',
    'precio_neto_unidad',
    'nombre',
    'id_simulacion'
  ];

  protected $guarded = [

  ];
}
