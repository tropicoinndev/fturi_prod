<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportesPanelRequest extends FormRequest
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
        $rules = [
            'inicio' => ['required', 'date'],
            'fin' => ['required', 'date'],
        ];

        if ($this->input('some_field') == 1) {
            $rules['cajas_id'] = ['required', 'exists:cajas,id'];
        }

        if ($this->input('another_field') == 1) {
            $rules['turnos'] = ['required', 'exists:turnos,id'];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'inicio.required' => 'La fecha desde donde iniciará la búsqueda.',
            'inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fin.required' => 'La fecha final hasta donde llegará la búsqueda.',
            'fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'cajas_id.required_if' => 'El campo cajas_id es obligatorio cuando some_field es 1.',
            'turnos.required_if' => 'El campo turnos es obligatorio cuando another_field es 1.',
        ];
    }
}
