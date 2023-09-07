<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxRequest extends FormRequest
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
            'product_id'        => 'nullable|integer',
            'tax_name'          => 'required|string',
            'tax_percentage'    => 'nullable|integer',
            'is_active'         => 'required',
        ];
    }


    public function messages()
    {
        return [
            'tax_name.required'  => 'Tax is Required!',
            'tax_name.string'    => 'tax must be String Type!',
            'is_active.required' => 'Please select the tax status!',
        ];
    }


}
