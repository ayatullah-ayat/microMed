<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'about_titile'          => 'nullable|string',
            'about_description'     => 'nullable|string',
            'about_thumbnail'       => 'nullable|string',
            'is_active'             => 'required',
        ];
    }


    public function messages()
    {
        return [
            'about_title.string'        => 'About title must be String Type!',
            'about_description.string'  => 'About Description must be String Type!',
            'is_active.required'        => 'Please select the about active status!',
        ];
    }


}
