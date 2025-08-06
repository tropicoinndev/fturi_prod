<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorerecepcionesRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fecha_salida' => ['required', 'date'],
            'clientes_id' => ['required', 'integer'],
            'tarifas_id' => ['required', 'string'],
            'habitaciones_id' => ['required', 'string']
        ];
    }
}
