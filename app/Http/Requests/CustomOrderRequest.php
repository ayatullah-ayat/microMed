<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomOrderRequest extends FormRequest
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
            'customer_name'     => 'required|string',
            'customer_address'  => 'nullable|string',
            'customer_phone'    => 'nullable|string|unique:customers,customer_phone',
            'customer_address'  => 'nullable|string',
        ];
    }


    public function messages()
    {
        return [
            'customer_name.required'  => 'Customer name is Required!',
            'customer_name.string'    => 'Customer name must be String Type!',
            'customer_address.string' => 'Customer address must be String Type!',
            'customer_phone.unique'   => 'Customer phone must be Unique!',
            'is_active.required'      => 'Please select the supplier status!',
        ];
    }


}
