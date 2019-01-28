<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulacion_valores extends Model
{
  protected $table = 'Simulacion_valores';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'costo_parcial_neto',
    'costo_parcial_bruto',
    'ganancia_neta',
    'ganancia_bruta',
    'porcentaje_ganacia',
    'total_final_neto',
    'total_final_iva',
    'total_final_bruto',
    'id_simulacion'
  ];

  protected $guarded = [

  ];
}
