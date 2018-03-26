<?php

namespace CamaleonERP;

use Illuminate\Database\Eloquent\Model;

class Eventos_tienen_documentos extends Model
{
  protected $table = 'eventos_tienen_documentos';
  protected $primaryKey = 'id';
  public $timestamp = 'false';

  protected $fillable = [
    'id_evento',
    'id_documento',
  ];

  protected $guarded = [

  ];
}
