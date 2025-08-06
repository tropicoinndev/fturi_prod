<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorerosRequest extends FormRequest
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
            'comprobantes_id' => [
                'nullable',
                'required_if:correlativo,1',
                'string'
            ],
            'sucursales_id' => ['required', 'string'],
            'correlativo' => ['required', 'in:0,1'],
            'fecha' => ['required', 'date'],
            'forma_pagos_id' => ['required', 'integer'],
            'monto' => ['required', 'numeric', 'min:0'],
            'clase' => ['required', 'string'],
            'nombre' => ['required', 'string'],
            'identificaciones' => ['nullable'],
            'numero' => ['nullable', 'string'],
            'observaciones' => ['required', 'string'],
            'cargo' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'comprobantes_id.required_if' => 'El campo comprobantes_id es obligatorio cuando el correlativo es igual a 1.',
            'sucursales_id.required' => 'El campo sucursales_id es obligatorio cuando el correlativo es igual a 0.',
            'correlativo.required' => 'El campo correlativo es obligatorio.',
            'correlativo.in' => 'El campo correlativo solo puede ser 0 o 1.',
            'fecha.required' => 'El campo fecha es obligatorio.',
            'fecha.date' => 'El campo fecha debe ser una fecha válida.',
            'forma_pagos_id.required' => 'El campo forma_pagos_id es obligatorio.',
            'forma_pagos_id.integer' => 'El campo forma_pagos_id debe ser un número entero.',
            'monto.required' => 'El campo monto es obligatorio.',
            'monto.numeric' => 'El campo monto debe ser un número.',
            'monto.min' => 'El campo monto debe ser mayor o igual a 0.',
            'clase.required' => 'El campo clase es obligatorio.',
            'clase.string' => 'El campo clase debe ser un texto.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.string' => 'El campo nombre debe ser un texto.',
            'identificaciones.required' => 'El campo identificaciones es obligatorio.',
            'identificaciones.integer' => 'El campo identificaciones debe ser un número entero.',
            'numero.required' => 'El campo número es obligatorio.',
            'numero.string' => 'El campo número debe ser un texto.',
            'observaciones.required' => 'El campo observaciones es obligatorio.',
            'observaciones.string' => 'El campo observaciones debe ser un texto.',
            'cargo.required' => 'El campo cargo es obligatorio.',
            'cargo.string' => 'El campo cargo debe ser un texto.',
        ];
    }
}
