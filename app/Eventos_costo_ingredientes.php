<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_costo_ingredientes extends Model
{
  protected $table = 'eventos_costo_ingredientes';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'id_ingrediente',
    'precio_bruto',
    'costo_total'
  ];

  protected $guarded = [

  ];
}
