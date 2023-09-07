<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomServiceProductRequest extends FormRequest
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
            'product_name'          => 'required|string',
            'product_description'   => 'nullable|string',
            'product_thumbnail'     => 'nullable',
            'is_active'             => 'required',
        ];
    }


    public function messages()
    {
        return [
            'product_name.required'         => 'Product is Required!',
            'product_name.string'           => 'Product must be String Type!',
            'product_description.string'    => 'Description Must be String Type!',
            'is_active.required'            => 'Please select the Product status!',
        ];
    }

    
}
