<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name'      => 'required|string',
            'email'     => 'nullable|string',
            'phone'     => 'required|string',
            'subject'   => 'required|string',
            'message'   => 'required|string|unique:contacts,message'
        ];
    }


    public function messages()
    {
        return [
            'name.required'     => 'Name is Required!',
            'name.string'       => 'Name must be String Type!',
            'email.string'      => 'email must be String',
            'phone.string'      => 'Phone must be string',
            'message.required'  => 'Message is Required',
            'message.unique'    => 'Message must be unique',
            'message.string'    => 'Message must be strign type',
        ];
    }


}
