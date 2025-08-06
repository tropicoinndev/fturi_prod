<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatesucursalesRequest extends FormRequest
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
            #'codigo'                =>['required','numeric','unique:sucursales','min:1','max:9999'],
            'logo'                  =>['nullable','image','mimes:png,jpg','max:2048'],#Reglas para la imagen
            'sucursal'              =>['required','string','min:3','max:100'],
            'direccion'             =>['required','string','min:6','max:255'],
            'telefono'              =>['required','string','size:8'],
            'correo'                =>['required','email','min:3','max:50'],
            'giro'                  =>['required','string','min:3','max:255'],
            'nit'                   =>['required','string','min:3','max:14'],
            'municipios_id'         =>['required','integer'],
            'codigo_establecimiento'=>['required','string','min:3','max:10'],
            'nrc'                   =>['required','string','min:3','max:9'],
            'matriz'                =>['required','boolean']
        ];
    }

    public function messages()
    {
        return [
            'required'      => 'El campo :attribute es requerido.',
            'alpha'         => 'El campo :attribute solo acepta letras (no números, no espacios en blanco ni carácteres especiales).',
            'alpha_num'     => 'El campo :attribute solo acepta letras y números (no espacios en blanco ni carácteres especiales).',
            'string'        => 'El campo :attribute acepta letras, números, espacios en blanco y carácteres especiales.',
            'size'          => 'El campo :attribute debe tener exactamente :size carácteres.',
            'integer'       => 'El campo :attribute solo acepta números enteros (no decimales).',
            'digits'        => 'El campo :attribute debe tener exactamente :digits dígitos.',
            'numeric'       => 'El campo :attribute acepta números enteros y decimales.',
            'unique'        => 'El campo :attribute ya existe en la Base de Datos.',
            'min'           => 'El campo :attribute debe contener almenos :min carácteres.',
            'max'           => 'El campo :attribute acepta :max carácteres como máximo.',
            'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
        ];
    }
}
