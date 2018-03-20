<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
  protected $table = 'inventario';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_item',
    'cantidad',
    'unidad'
  ];

  protected $guarded = [

  ];
}
