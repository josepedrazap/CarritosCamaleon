<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Vehiculos extends Model
{
  protected $table = 'vehiculos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'marca',
    'modelo',
    'patente',
    'color',
    'alias',
    'condicion'
  ];

  protected $guarded = [

  ];
}
