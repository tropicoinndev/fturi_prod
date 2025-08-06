<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storetipo_serviciosRequest extends FormRequest
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
            'tipo_servicio'=>['required','string','unique:tipo_servicios','min:3','max:255'],
            'token'        =>['required','numeric','unique:tipo_servicios','min:1','max:99999']
        ];
    }

    public function messages()
    {
        return [
            'required'=>'El campo :attribute es requerido.',
            'string'  =>'El campo :attribute debe contener letras o numeros.',
            'numeric' =>'El campo :attribute debe ser numerico.',
            'unique'  =>'Este registro ya existe en la Base de Datos.',
            'min'     =>'El campo :attribute debe contener almenos :min caracteres.',
            'max'     =>'El campo :attribute acepta :max caracteres como maximo.'
        ];
    }
}
