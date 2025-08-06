<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Updatecorrelativo_sucursalRequest extends FormRequest
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
            'actual'=>['numeric','digits_between:1,15'],
        ];
    }

    public function messages(){
        return [
            'required'      =>'El campo :attribute es requerido.',
            'string'        =>'El campo :attribute debe contener letras o números.',
            'numeric'       =>'El campo :attribute debe ser numérico.',
            'unique'        =>'Este registro ya existe en la Base de Datos.',
            'min'           =>'El campo :attribute debe contener almenos :min carácteres.',
            'max'           =>'El campo :attribute acepta :max carácteres como máximo.',
            'digits_between'=>'El campo :attribute solo admite entre 1 y 15 dígitos.',
        ];
    }
}
