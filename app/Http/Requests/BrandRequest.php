<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
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
            'brand_name'         => 'required|string',
            'brand_description'  => 'nullable|string',
            'brand_image'        => 'nullable',
            'is_active'          => 'required',
        ];
    }


    public function messages()
    {
        return [
            'brand_name.required'        => 'Brand is Required!',
            'brand_name.string'          => 'Brand must be String Type!',
            'brand_description.string'   => 'Description Must be String Type!',
            'is_active.required'         => 'Please select the category status!',
        ];
    }
}
