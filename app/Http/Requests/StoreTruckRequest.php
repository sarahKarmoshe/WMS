<?php

namespace App\Http\Requests;

use App\Models\Department;
use http\Message\Body;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class StoreTruckRequest extends FormRequest
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
            'state'=>['required'],
            'model'=>['required'],
            'color'=>['required'],
            'number'=>['required'],
            'department_id'=>['required'],
        ];
    }
}
