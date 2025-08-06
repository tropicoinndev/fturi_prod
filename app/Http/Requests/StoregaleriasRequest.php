<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoregaleriasRequest extends FormRequest
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
            'foto' => 'required|image|mimes:png,jpg|max:2048', // Reglas para la imagen
            'descripcion' => 'required|string|max:200', // Reglas para la descripción
        ];
    }
    public function messages()
    {
        return [
            'foto.required' => 'La imagen es obligatoria.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo: png, jpg.',
            'foto.max' => 'La imagen no puede ser mayor de 2 MB.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'descripcion.max' => 'La descripción no puede ser mayor de 200 caracteres.',
        ];
    }
}
