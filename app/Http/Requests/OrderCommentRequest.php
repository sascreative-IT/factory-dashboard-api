<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCommentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'comments' => 'required',
            'added_by_name' => 'required',
            'added_by_email' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'comments.required' => 'The comments field is required',
            'added_by_name.required' => 'The added by name field is required',
            'added_by_email.required' => 'The added by email field is required',
        ];
    }
}
