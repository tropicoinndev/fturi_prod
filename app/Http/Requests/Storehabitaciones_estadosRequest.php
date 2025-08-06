<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storehabitaciones_estadosRequest extends FormRequest
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
            "estado_habitaciones_id" => ['required', 'integer'],
            "estado_habitaciones_id" => ['required', 'integer'],
            "justificacion" => ['required', 'string']
        ];
    }
}
