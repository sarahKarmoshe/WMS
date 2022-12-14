<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'start_time' => ['required'],
            'end_time' => ['required'],
            'staff_ids' => ['required'],
            'truck_ids' => ['required'],
            'department_id' => ['required'],
            'user_id' => ['required'],
        ];
    }
}
