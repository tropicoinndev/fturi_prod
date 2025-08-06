<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storepersonas_alertasRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(){
        return [
            'nombres'=>['nullable','string','min:2','max:100'],
            'apellidos'=>['nullable','string','min:2','max:100'],
            'alias'=>['nullable','string','min:2','max:100'],
            'numero_identificacion'=>['nullable','string','min:2','max:100'],
        ];
    }

    public function messages(){
        return [
            'required'      =>'El campo :attribute es requerido.',
            'string'        =>'El campo :attribute debe contener letras o números.',
            'numeric'       =>'El campo :attribute debe ser numérico.',
            'unique'        =>'El campo :attribute ya existe en la Base de Datos.',
            'min'           =>'El campo :attribute debe contener almenos :min carácteres.',
            'max'           =>'El campo :attribute acepta :max carácteres como máximo.',
            'digits_between'=>'El campo :attribute solo admite entre 1 y 15 dígitos.',
        ];
    }
}
