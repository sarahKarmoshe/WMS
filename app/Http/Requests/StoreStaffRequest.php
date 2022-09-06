<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
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
            'name'=>['required'],
            'phone'=>['required'],
            'birth_date'=>['required'],
            'rate'=>['required'],
            'department_id'=>['required'],
            'photo'=>['required', 'image:jpeg,png,jpg,gif,bmp,svg', 'max:2048']

        ];
    }
}
