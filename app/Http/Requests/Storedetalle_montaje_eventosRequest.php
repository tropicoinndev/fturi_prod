<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storedetalle_montaje_eventosRequest extends FormRequest
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
            'tipo_mesa'  => ['nullable', 'string', 'min:3', 'max:200'],
            'bandera'      => ['nullable', 'string', 'min:3', 'max:200'],
            'rotafolio_plumon'     => ['nullable', 'int', 'min:1'],
            'podium'     => ['nullable', 'string', 'min:1', 'max:200'],
            'pista_baile'     => ['nullable', 'string', 'min:1', 'max:200'],
            'tarima'     => ['nullable', 'string', 'min:1', 'max:200'],
            'equipo_montar'     => ['nullable', 'string', 'min:1', 'max:200'],
            'otros'     => ['nullable', 'string', 'min:1', 'max:200'],
            'confirm' => ['required', 'in:1'],
            'eventos_id' => ['required', 'string', 'max:256'],
        ];

    }
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'string'  => 'El campo :attribute debe contener letras o numeros.',
            'numeric' => 'El campo :attribute debe ser numerico.',
            'unique'  => 'Este registro ya existe en la Base de Datos.',
            'min'     => 'El campo :attribute debe contener almenos :min caracteres.',
            'max'     => 'El campo :attribute acepta :max caracteres como maximo.'
        ];
    }
}
