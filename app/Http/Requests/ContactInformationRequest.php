<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactInformationRequest extends FormRequest
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
            'title'             => 'nullable|string',
            'description'       => 'nullable|string',
            'contact_address'   => 'nullable|string',
            'contact_email'     => 'nullable|string',
            'contact_phone'     => 'nullable|string',
            'is_active'         => 'required',
        ];
    }


    public function messages()
    {
        return [
            'title.string'              => 'Contact title must be String Type!',
            'description.string'        => 'Contact Description must be String Type!',
            'contact_address.string'    => 'Contact Address must be String Type!',
            'contact_email.string'      => 'Contact Email must be String Type!',
            'contact_phone.string'      => 'Contact Email must be String Type!',
            'is_active.required'        => 'Please select the about active status!',
        ];
    }

}
