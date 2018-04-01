<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_detalle extends Model
{
  protected $table = 'eventos_detalle';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'gasto_extra',
    'iva_por_pagar',
    'precio_evento',
    'pago_cocineros',
    'total_ingredientes',
    'total_ingredientes_iva',
    'utilidad_final',
    'costo_final',
    
    'total_productos',
    'total_productos_iva'
  ];

  protected $guarded = [

  ];
}
