<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storemh_contingenciasRequest extends FormRequest
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
            'id' => ['required', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'hora_inicio' => ['required', 'date_format:H:i:s'],
            'hora_fin' => ['required', 'date_format:H:i:s', 'after:hora_inicio'],
            'tipo_contingencia' => ['required', 'numeric', 'in:1,2,3,4,5'],
            'motivo_contingencia' => ['nullable', 'required_if:tipo_contingencia,5', 'string', 'max:200'],
            'confirmar' => ['accepted'],
            'opcion' => ['required', 'numeric'],
        ];
    }
    public function messages()
    {
        return [
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener el formato HH:MM:SS.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.date_format' => 'La hora de fin debe tener el formato HH:MM:SS.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'tipo_contingencia.required' => 'El tipo de contingencia es obligatorio.',
            'tipo_contingencia.numeric' => 'El tipo de contingencia debe ser un número.',
            'tipo_contingencia.in' => 'El tipo de contingencia seleccionado no es válido.',
            'motivo_contingencia.required_if' => 'El motivo de la contingencia es obligatorio cuando el tipo de contingencia es "Otro".',
            'motivo_contingencia.string' => 'El motivo de la contingencia debe ser una cadena de texto.',
            'motivo_contingencia.max' => 'El motivo de la contingencia no puede exceder los 500 caracteres.',
            'confirmar.accepted' => 'Debe aceptar la confirmación para continuar.',
            'opcion.required' => 'La opción es obligatoria.',
            'opcion.numeric' => 'La opción debe ser un número.',
        ];
    }
}
