<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Cuentas_mov_facturas extends Model
{
  protected $table = 'cuentas_mov_facturas';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_cuenta',
    'id_factura',
    'debe',
    'haber',
    'fecha',
    'glosa'
  ];

  protected $guarded = [

  ];
}
