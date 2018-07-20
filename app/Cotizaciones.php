<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Cotizaciones extends Model
{
  protected $table = 'cotizaciones';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'nombre_cliente',
    'descripcion',
    'direccion',
    'fecha',
    'estado'
  ];

  protected $guarded = [

  ];
}
