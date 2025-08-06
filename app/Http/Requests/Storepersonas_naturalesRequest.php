<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storepersonas_naturalesRequest extends FormRequest
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
            'apellidos'          =>['required','string','max:250'],
            'nombre'             =>['required','string','max:250'],
            'profesion'          =>['nullable','string','max:255'],
            'nacimiento'         =>['nullable','string','max:250'],
            'departamentos_id'   =>['nullable','integer'],
            'fecha_nacimiento'   =>['nullable','date'],
            'paises_id'          =>['nullable','integer'],
            'estado_civil'       =>['nullable','string','max:50'],
            'identificaciones_id'=>['required','exists:identificaciones,id'],
            'identificacion'     =>['required','string','max:50'],
            'domicilio'          =>['nullable','string','max:250'],
            'observaciones'      =>['nullable','string'],
            'persona_riesgo'     =>['nullable','boolean'],
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
