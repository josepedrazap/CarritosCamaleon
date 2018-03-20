<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
  protected $table = 'proveedores';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'nombre',
    'email',
    'telefono',
    'descripcion',
    'rut'
  ];

  protected $guarded = [

  ];
}
