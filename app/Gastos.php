<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
  protected $table = 'gastos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'descripcion',
    'fecha',
    'condicion',
    'pagador',
    'id_documento'
  ];

  protected $guarded = [

  ];
}
