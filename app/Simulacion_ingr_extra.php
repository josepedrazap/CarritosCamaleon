<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulacion_ingr_extra extends Model
{
  protected $table = 'simulacion_ingr_extra';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_extra',
    'cantidad',
    'precio_neto_unidad',
    'costo_neto_unidad',
    'id_simulacion'
  ];

  protected $guarded = [

  ];
}
