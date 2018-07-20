<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class AuxModel extends Model
{
  protected $table = 'aux';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'var',
    'id_buscado'
  ];

  protected $guarded = [

  ];
}
