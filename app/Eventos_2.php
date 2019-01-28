<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_2 extends Model
{
  protected $table = 'eventos_2';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'direccion',
    'fecha_hora',
    'condicion',
    'id_cliente',
    'id_simulacion',
    'cliente',
    'descripcion',
    'estado_pago'
  ];

}
