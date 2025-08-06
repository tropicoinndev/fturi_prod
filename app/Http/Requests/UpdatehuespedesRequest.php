<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatehuespedesRequest extends FormRequest
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
            'nombre' => ['required', 'string'],
            'telefono' => ['nullable', 'numeric', 'min:8'],
            'identificacion' => ['nullable', 'string', 'required_with:identificaciones_id',],
            'identificaciones_id' => ['nullable', 'integer', 'required_with:identificacion'],
            'municipios_id' => ['nullable'],
            'paises_id' => ['nullable'],
        ];
    }
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es requerido.',
            'string'  => 'El campo :attribute debe contener letras o numeros.',
            'numeric' => 'El campo :attribute debe ser numerico no debe contener guiones.',
            'unique'  => 'Este huesped ya existe en la base de datos.',
            'min'     => 'El campo :attribute debe contener almenos :min caracteres.',
            'max'     => 'El campo :attribute acepta :max caracteres como maximo.'
        ];
    }
}
