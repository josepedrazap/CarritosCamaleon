<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Selects_valores extends Model
{
  protected $table = 'selects_valores';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'valor',
    'familia',
    'condicion'
  ];

  protected $guarded = [

  ];
}
