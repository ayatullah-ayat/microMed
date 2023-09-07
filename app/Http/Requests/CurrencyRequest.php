<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
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
            'currency_name'             => 'required|string',
            'currency_icon'             => 'nullable|string',
            'currency_position'         => 'nullable|string', 
            'currency_conversion_rate'  => 'nullable|integer', 
            'is_active'                 => 'required',
        ];
    }

    public function messages()
    {
        return [
            'currency_name.required'  => 'Currency is Required!',
            'currency_name.string'    => 'Currency Name must be String Type!',
            'is_active.required' => 'Please select the tax status!',
        ];
    }


}
