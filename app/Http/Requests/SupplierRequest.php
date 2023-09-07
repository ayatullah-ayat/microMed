<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'supplier_name'     => 'required|string',
            'supplier_email '   => 'nullable|string',
            'supplier_phone '   => 'nullable|integer',
            'supplier_address ' => 'nullable|string',
            'is_active'         => 'required',
        ];
    }


    public function messages()
    {
        return [
            'supplier_name.required'  => 'Supplier name is Required!',
            // 'supplier_name.string'    => 'Supplier name must be String Type!',
            // 'supplier_email.string'   => 'Supplier email must be String Type!',
            'supplier_address.string' => 'Supplier address must be String Type!',
            'is_active.required'      => 'Please select the supplier status!',
        ];
    }

    
}
