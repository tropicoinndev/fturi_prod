<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateidentificacionesRequest extends FormRequest
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
            'identificacion' => ['required', 'string', 'min:3', 'max:50'],
            'regex'         => ['required', 'string', 'min:5', 'max:50'],
            'info'          => ['required', 'string', 'min:3', 'max:200'],
            'codigo'        => ['required', 'string'],
            'tipo_cliente'  => ['required'],
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
