<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebFooterRequest extends FormRequest
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
            'footer_about'  => 'required|string',
            'footer_logo'   => 'nullable|string',
            'is_active'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'footer_about.required' => 'Footer About is Required!',
            'footer_about.string'   => 'Footer About must be string type!',
            'is_active.required'    => 'Please select the Footer about status!',
        ];
    }


}
