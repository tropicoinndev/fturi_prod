<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storedetalles_sujeto_excluidosRequest extends FormRequest
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
            'id' => [
                'required',
                'string',
                'min:150'
            ],
            'concepto' => [
                'required',
                'string',
                'min:5',
                'max:250'
            ],
            'opcion' => [
                'required',
                'numeric'
            ],
            'tipo_item' => [
                'required',
                'numeric',
                'min:1',
                'max:3'
            ],
            'unidad_medida' => [
                'required',
                'numeric',
                'min:1',
                'max:99'
            ],
            'cantidad' => [
                'required',
                'numeric',
                'min:1',
            ],
            'precio_unitario' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'renta' => [
                'nullable',
                'numeric',
                'min:0.01',
            ],
            'total' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ];
    }

    public function messages()
    {
        return [
            'concepto.required' => 'El campo Concepto es obligatorio.',
            'concepto.string' => 'El campo Concepto debe ser una cadena de texto.',
            'concepto.min' => 'El campo Concepto debe tener al menos 5 caracteres.',
            'concepto.max' => 'El campo Concepto no debe exceder los 250 caracteres.',

            'opcion.required' => 'El campo Opción es obligatorio.',
            'opcion.numeric' => 'El campo Opción debe ser un número.',

            'tipo_item.required' => 'El campo Tipo de Item es obligatorio.',
            'tipo_item.numeric' => 'El campo Tipo de Item debe ser un número.',
            'tipo_item.min' => 'El campo Tipo de Item debe ser al menos 1.',
            'tipo_item.max' => 'El campo Tipo de Item no debe exceder de 3.',

            'unidad_medida.required' => 'El campo Unidad de Medida es obligatorio.',
            'unidad_medida.numeric' => 'El campo Unidad de Medida debe ser un número.',
            'unidad_medida.min' => 'El campo Unidad de Medida debe ser al menos 1.',
            'unidad_medida.max' => 'El campo Unidad de Medida no debe exceder de 99.',

            'cantidad.required' => 'El campo Cantidad es obligatorio.',
            'cantidad.numeric' => 'El campo Cantidad debe ser un número.',
            'cantidad.min' => 'El campo Cantidad debe ser al menos 1.',

            'precio_unitario.required' => 'El campo Precio Unitario es obligatorio.',
            'precio_unitario.numeric' => 'El campo Precio Unitario debe ser un número.',
            'precio_unitario.min' => 'El campo Precio Unitario debe ser al menos 0.01.',

            'renta.numeric' => 'El campo Renta debe ser un número.',
            'renta.min' => 'El campo Renta debe ser al menos 0.01.',

            'total.required' => 'El campo Total es obligatorio.',
            'total.numeric' => 'El campo Total debe ser un número.',
            'total.min' => 'El campo Total debe ser al menos 0.01.',
        ];
    }
}
