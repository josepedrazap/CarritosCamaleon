<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Inventario_entrada extends Model
{
  protected $table = 'inventario_entrada';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'cantidad',
    'monto',
    'id_item',
    'descripcion'
  ];

  protected $guarded = [

  ];
}
