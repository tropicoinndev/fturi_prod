<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storedetalle_contribuyentesRequest extends FormRequest
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
            'juridico'   =>['required','string','min:3','max:255'],
            'clientes_id'=>['required','numeric'],
            'nrc'        =>['nullable','min:1','max:9','regex:/^[1-9][0-9]*(-[1-9][0-9]*)?$/'],
        ];
    }
    public function withValidator($validator)
    {
        $validator->sometimes('nrc', 'string|regex:/^[1-9][0-9]*(-[1-9][0-9]*)?$/', function ($input) {
            return !is_null($input->nrc);
        });
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
            'regex'         =>'El campo :attribute no cumple con las reglas de validación.'
        ];
    }
}
