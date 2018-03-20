<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
  protected $table = 'compra';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'monto',
    'id_proveedor',
    'tipo',
    'comprobante'
  ];

  protected $guarded = [

  ];
}
