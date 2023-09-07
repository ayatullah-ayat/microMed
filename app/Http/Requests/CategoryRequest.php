<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category_name'         => 'required|string',
            'category_description'  => 'nullable|string',
            'category_image'        => 'nullable',
            'is_active'             => 'required',
        ];
    }


    public function messages()
    {
        return [
            'category_name.required'        => 'Category is Required!',
            'category_name.string'          => 'Category must be String Type!',
            'category_description.string'   => 'Description Must be String Type!',
            'is_active.required'            => 'Please select the category status!',
        ];
    }

    
}
