<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Inventario_salida extends Model
{
  protected $table = 'inventario_salida';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'cantidad',
    'tipo',
    'id_item',
    'id_evento',
    'id_aux'
  ];

  protected $guarded = [

  ];
}
