<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_tienen_ingr_extras extends Model
{
  protected $table = 'eventos_tienen_ingr_extras';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'id_extra',
    'precio',
    'costo',
    'cantidad'
  ];

  protected $guarded = [

  ];
}
