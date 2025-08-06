<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storeanulacion_comprobantesRequest extends FormRequest
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
     *
     * cSpell:ignore observacion
     */
    public function rules()
    {
        return [
            'anulaciones_id' => ['required', 'string'],
            'observacion' => ['required', 'string', 'max:200'],
            'confirm' => ['required', 'numeric',],
        ];
    }
    public function messages()
    {
        return [
            'anulaciones_id.required' => 'Seleccione una opción de anulaciones validad, es requerido.',
            'observacion.required' => 'El campo de observación es obligatorio.',
            'observacion.string' => 'El campo de observación debe ser una cadena de texto.',
            'observacion.max' => 'El campo de observación no puede tener más de 200 caracteres.',
            'confirm.required' => 'Debe confirmar que quiere anular el comprobante para continuar',
        ];
    }
}
