<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'status.required' => 'The order id field is required'
        ];
    }
}
