<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Simulaciones extends Model
{
  protected $table = 'simulaciones';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'descripcion',
    'fecha',
    'nombre'
  ];

  protected $guarded = [

  ];
}
