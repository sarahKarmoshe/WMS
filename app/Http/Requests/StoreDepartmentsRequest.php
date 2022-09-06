<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentsRequest extends FormRequest
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
            'name'=>['required','string'],
            'capacity'=>['required','numeric'],
            'shipping_cost'=>['required','numeric'],
            'capital'=>['required','numeric'],
            'profit_balance'=>['required','numeric'],
            'basic_balance'=>['required','numeric'],
            'payments'=>['required','numeric'],
            'de_type_id'=>['required','numeric'],
            'admin_id'=>['required','numeric'],

        ];
    }
}
