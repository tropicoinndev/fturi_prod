<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateordenesRequest extends FormRequest
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
            'id_orden'=>['required','numeric'],
            'clientes_id'=>['required','numeric']
            /*'fecha'       =>['required'],
            'titular'     =>['required','string','min:3','max:100'],
            'descripcion' =>['required','string','min:3','max:255'],
            'cajas_id'    =>['required','numeric']*/
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
