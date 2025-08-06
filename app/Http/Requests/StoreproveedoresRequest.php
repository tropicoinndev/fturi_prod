<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreproveedoresRequest extends FormRequest
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
            'proveedor'    => ['required', 'string', 'unique:proveedores', 'min:3', 'max:100'],
            'nrc'          => ['nullable', 'string', 'unique:proveedores', 'min:1', 'max:8', 'regex:/^[0-9]{1,8}$/'],
            'nit'          => ['nullable', 'string', 'unique:proveedores', 'min:9', 'max:14', 'regex:/^[0-9]{14}$/'],
            'dui'          => ['nullable', 'string', 'unique:proveedores', 'min:3', 'max:9', 'regex:/^[0-9]{9}$/'],
            'direccion'    => ['required', 'string', 'min:3', 'max:255'],
            'municipios_id' => ['required', 'numeric'],
            'contactos'    => ['required', 'string', 'min:3', 'max:100'],
            'informacion'  => ['required', 'string', 'min:3', 'max:100'],
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
