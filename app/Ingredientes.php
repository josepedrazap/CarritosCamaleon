<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Ingredientes extends Model
{
  protected $table = 'ingredientes';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'nombre',
    'clase',
    'tipo',
    'unidad',
    'condicion',
    'inventareable',
    'precio_bruto',
    'precio_liquido',
    'iva'
  ];

  protected $guarded = [

  ];
}
