<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_tienen_trabajadores extends Model
{
  protected $table = 'eventos_tienen_trabajadores';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'id_trabajador',
    'monto',
    'estado'
  ];

  protected $guarded = [

  ];
}
