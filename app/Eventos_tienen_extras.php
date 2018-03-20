<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_tienen_extras extends Model
{
  protected $table = 'eventos_tienen_extras';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'id_extra',
    'precio',
    'costo'
  ];

  protected $guarded = [

  ];
}
