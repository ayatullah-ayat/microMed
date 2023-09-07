<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
            'subcategory_name'         => 'required|string',
            'subcategory_description'  => 'nullable|string',
            'subcategory_image'        => 'nullable',
            'is_active'                => 'required',
        ];
    }

    public function messages()
    {
        return [
            'subcategory_name.required'         => 'Sub Category is Required!',
            'subcategory_name.string'           => 'Category must be String Type!',
            'subcategory_description.string'    => 'Description must be String Type!',
            'is_active.required'                => 'Please select the category status!',
        ];
    }

}
