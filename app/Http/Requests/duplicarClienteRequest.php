<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class duplicarClienteRequest extends FormRequest
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
            'clientes_id' => ['required', 'string', 'max:256'],
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'direccion' => ['required', 'string', 'min:7', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'min:6', 'max:200'],
            'municipios_id' => ['required', 'integer', 'exists:municipios,id'],
        ];
    }
    public function messages()
    {
        return [
            'clientes_id.required' => 'Debe enviarse el ID del cliente a duplicar.',
            'nombre.required' => 'Debe registrar un nombre para el cliente.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 7 caracteres.',
            'direccion.max' => 'La dirección no puede exceder los 255 caracteres.',
            'email.required' => 'Debe proporcionar un correo electrónico válido.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',
            'municipios_id.required' => 'Debe seleccionar un municipio.',
            'municipios_id.exists' => 'El municipio seleccionado no es válido.',
        ];
    }

}
