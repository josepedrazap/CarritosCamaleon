<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
  protected $table = 'gastos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'descripcion',
    'tipo',
    'fecha',
    'monto_gasto',
    'valor_real',
    'iva',
    'condicion',
    'pagador'
  ];

  protected $guarded = [

  ];
}
