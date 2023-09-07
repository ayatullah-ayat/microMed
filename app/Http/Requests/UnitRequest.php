<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
            'unit_name'        => 'required|string',
            'unit_short_name'  => 'nullable|string',
            'is_active'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'unit_name.required'  => 'Unit is Required!',
            'variant_name.string'    => 'Unit must be String Type!',
            'is_active.required'     => 'Please select the Unit status!',
        ];
    }
}
