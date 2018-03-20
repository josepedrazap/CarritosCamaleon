<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Trabajadores extends Model
{
  protected $table = 'trabajadores';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'nombre',
    'apellido',
    'clase',
    'condicion'
  ];

  protected $guarded = [

  ];
}
