<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreanticiposRequest extends FormRequest
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
            "concepto" => ["required", "string", "max:300"],
            "clientes_id" => ["required", "integer"],
            "monto" => ["required", "numeric"],
            "fecha_aplicacion" => ["required", "date"],
        ];
    }
}
