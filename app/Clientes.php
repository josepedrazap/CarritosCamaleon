<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
  protected $table = 'clientes';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'contacto',
    'mail',
    'nombre',
    'apellido',
    'rut'
  ];

  protected $guarded = [

  ];
}
