<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreclientesRequest extends FormRequest
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
            'nombre'        => ['required', 'string', 'min:3', 'max:250'],
            'email'         => ['nullable', 'string', 'email', 'min:8', 'max:50'],
            'direccion'     => ['required', 'string', 'min:6', 'max:200'],
            'observaciones' => ['nullable', 'string'],
            'tipo_cliente'  => ['required', 'numeric'],
            'categoria'     => ['nullable', 'numeric'],
            'actividades_economicas_id' => ['nullable', 'integer'],
            'municipios_id' => ['nullable'],
            'extranjeros_id' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'required'      => 'El campo :attribute es requerido.',
            'string'        => 'El campo :attribute debe contener letras o números.',
            'numeric'       => 'El campo :attribute debe ser numérico.',
            'unique'        => 'El campo :attribute ya existe en la Base de Datos.',
            'min'           => 'El campo :attribute debe contener almenos :min carácteres.',
            'max'           => 'El campo :attribute acepta :max carácteres como máximo.',
            'digits_between' => 'El campo :attribute solo admite entre 1 y 15 dígitos.',
        ];
    }
}
