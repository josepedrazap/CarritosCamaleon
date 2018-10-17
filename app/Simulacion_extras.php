<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulacion_extras extends Model
{
  protected $table = 'simulacion_extras';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_extra',
    'precio_neto_unidad',
    'costo_neto_unidad',
    'id_simulacion'
  ];

  protected $guarded = [

  ];
}
