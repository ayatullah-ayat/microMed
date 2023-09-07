<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ratting'=> 'required|integer',
            'comment'=> 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'ratting.required'  => 'Please Select Review!',
            'ratting.integer'   => 'Invalid Review Type!',
            'comment.required'     => 'Comment Should not be null!',
            'comment.string'       => 'Comment must be String!',
        ];
    }


}
