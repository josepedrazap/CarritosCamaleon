<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_tienen_productos extends Model
{
  protected $table = 'eventos_tienen_productos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'id_producto',
    'cantidad',
    'precio_a_cobrar'
  ];

  protected $guarded = [

  ];
}
