<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreempleadosRequest extends FormRequest
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
            'nombre_completo' => ['required', 'string', 'min:3', 'max:100'],
            'numero_documento' => ['required', 'string', 'min:1', 'max:20'],
            'identificaciones_id' => ['required'],
            'users_id' => ['nullable'],
        ];
    }
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'unique' => 'El campo :attribute es unico no pueden haber email duplicados.',
            'string'  => 'El campo :attribute debe contener letras o numeros.',

            'min'     => 'El campo :attribute debe contener almenos :min caracteres.',
            'max'     => 'El campo :attribute acepta :max caracteres como maximo.'
        ];
    }
}
