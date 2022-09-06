<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'description'=>['required','string'],
            'photo'=>['required','image:jpeg,png,jpg,gif,svg', 'max:2048'],
            'max_quantity'=>['required','numeric'],
            'min_quantity'=>['required','numeric'],
            'space'=>['required','numeric'],
            'measurement_unit'=>['required','string'],
            'exist_quantity'=>['required','numeric'],
            'products_number_by_space'=>['required','numeric'],
            'department_id'=>['required','numeric'],
            'category_id'=>['required','numeric'],
        ];
    }
}
