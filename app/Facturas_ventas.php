<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Facturas_ventas extends Model
{
  protected $table = 'facturas_ventas';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_proveedor',
    'tipo_documento',
    'numero_documento',
    'fecha_documento',
    'monto_neto',
    'iva',
    'total'
  ];

  protected $guarded = [

  ];
}
