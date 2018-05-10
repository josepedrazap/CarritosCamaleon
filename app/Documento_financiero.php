<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Documento_financiero extends Model
{
  protected $table = 'documento_financiero';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_tercero',
    'tipo_documento',
    'numero_documento',
    'fecha_documento',
    'fecha_ingreso',
    'tipo_dato',
    'monto_neto',
    'iva',
    'total',
    'tipo_tercero',
  ];

  protected $guarded = [

  ];
}
