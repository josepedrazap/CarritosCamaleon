<?php

namespace CamaleonERP\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrabajadoresFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'nombre_trabajador' => 'required',
          'apellido_trabajador' => 'required',
          'telefono_trabajador' => 'required',
          'email_trabajador' => 'required',
          'numero_cuenta' => 'required',
          'rut' => 'required',
          'maneja' => 'required'
        ];
    }
}
