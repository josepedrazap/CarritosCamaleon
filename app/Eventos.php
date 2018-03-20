<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
  protected $table = 'eventos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'contacto',
    'direccion',
    'fecha_hora',
    'fecha_despacho',
    'email',
    'nombre_cliente',
    'condicion',
    'id_cliente',
    'aprobado',
    'descripcion'
  ];

  protected $guarded = [

  ];
}
