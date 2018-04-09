<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Productos_tienen_ingredientes extends Model
{
  protected $table = 'productos_tienen_ingredientes';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_producto',
    'id_ingrediente',
    'porcion',
    'unidad'
  ];

  protected $guarded = [

  ];
}
