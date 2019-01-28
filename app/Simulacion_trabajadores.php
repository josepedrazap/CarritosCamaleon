<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulacion_trabajadores extends Model
{
  protected $table = 'simulacion_trabajadores';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'nombre',
    'cantidad',
    'sueldo',
    'id_simulacion'
  ];

  protected $guarded = [

  ];
}
