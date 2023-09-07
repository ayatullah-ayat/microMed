<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomServiceRequest extends FormRequest
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
            'service_name'          => 'required|string',
            'service_description'   => 'nullable|string',
            'service_thumbnail'     => 'nullable',
            'is_active'             => 'required',
        ];
    }


    public function messages()
    {
        return [
            'service_name.required'         => 'Service is Required!',
            'service_name.string'           => 'Service must be String Type!',
            'service_description.string'    => 'Description Must be String Type!',
            'is_active.required'            => 'Please select the category status!',
        ];
    }


}
