<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotaCreditoRequest extends FormRequest
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
            'dtes_id' => ['required', 'string', 'min:200'],
            'observaciones' => ['nullable', 'string', 'max:200'],
            'confirm' => ['required', 'accepted'],
        ];
    }
}
