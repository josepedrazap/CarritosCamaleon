<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    public $timestamp = 'false';

    protected $fillable = [
      'nombre',
      'precio',
      'tipo',
      'condicion',
      'base'
    ];

    protected $guarded = [

    ];
}
