<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storejob_contingenciasRequest extends FormRequest
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
            'contingencias_id' => ['required', 'string'],
            'confirm' => ['accepted'],
        ];
    }
    public function messages()
    {
        return [
            "confirm.accepted" => "Debe confirmar antes de enviar la solicitud",
            "contingencias_id.required" => "Debe debe seleccionar un tipo de contingencia",
        ];
    }
}
