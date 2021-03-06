<?php

namespace CamaleonERP\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventosFormRequest extends FormRequest
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
          'direccion_cliente' => 'required|max:60',
          'fecha_cliente' => 'required',
        ];
    }
}
