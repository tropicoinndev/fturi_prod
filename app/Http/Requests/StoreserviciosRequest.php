<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreserviciosRequest extends FormRequest
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
    public function rules(){
        return [
            'rubros_id'      =>['required','numeric'],
            'servicio'       =>['required','string','min:3','max:100'],
            'precio_unitario'=>['required','numeric'],
        ];
    }

    public function messages(){
        return [
            'required'      =>'El campo :attribute es requerido.',
            'alpha'         =>'El campo :attribute solo acepta letras (no números, no espacios en blanco ni carácteres especiales).',
            'alpha_num'     =>'El campo :attribute solo acepta letras y números (no espacios en blanco ni carácteres especiales).',
            'string'        =>'El campo :attribute acepta letras, números, espacios en blanco y carácteres especiales.',
            'integer'       =>'El campo :attribute solo acepta números enteros (no decimales).',
            'digits'        =>'El campo :attribute debe tener exactamente :digits dígitos.',
            'numeric'       =>'El campo :attribute acepta números enteros y decimales.',
            'unique'        =>'El campo :attribute ya existe en la Base de Datos.',
            'min'           =>'El campo :attribute debe contener almenos :min carácteres.',
            'max'           =>'El campo :attribute acepta :max carácteres como máximo.',
            'digits_between'=>'El campo :attribute debe tener entre :min y :max dígitos.',
        ];
    }
}
