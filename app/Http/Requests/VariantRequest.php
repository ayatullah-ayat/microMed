<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariantRequest extends FormRequest
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
            'variant_name'  => 'required|string',
            'variant_type'  => 'nullable|string',
            'is_active'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'variant_name.required'  => 'Variant is Required!',
            'variant_name.string'    => 'Variant must be String Type!',
            'is_active.required'     => 'Please select the variant status!',
        ];
    }

    
}
