<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class eventoRequest extends FormRequest
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
            'detalles'=>['required','min:1'],
            'mesa' => ['required', 'numeric', 'min:1']

        ];
    }
    public function messages()
    {
        return [
            'name.string'  =>'El campo rol debe ser texto',
            'name.required'=>'El campo rol es requerido',
            'name.max'     =>'El campo rol solo acepta 100 caracteres',
        ];
    }
}
