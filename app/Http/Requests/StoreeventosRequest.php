<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreeventosRequest extends FormRequest
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
            'fecha' =>['required','date'],
            'fecha_fin' => ['required', 'date'],
            'inicio' =>['required', 'date_format:H:i'],
            'finalizacion' =>['required', 'date_format:H:i'],
            'minimo_personas'=>['required','numeric','min:1','max:99999'],
            'maximo_personas' =>['required','numeric', 'min:1', 'max:99999'],
            'fpago'   =>['required','numeric'],
            'clientes_id'   => ['nullable', ],
            'titular'   => ['nullable',],
            'Tipoevento'     =>['required','numeric'],
            'salones_seleccionados.*' => ['required', 'integer'],
            'salones_seleccionados'   => ['required', 'min:1'],
            'encargado'     => ['nullable'],
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
