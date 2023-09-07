<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficeAccountRequest extends FormRequest
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
            'date'          => 'string',
            'account_type'  => 'nullable|string',
            'description'   => 'string',
            'cash_in'       => 'integer',
            'cash_out'      => 'integer',
            'note'          => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'account_type.string'   => 'Account Type Must be String Type!',
            'description.string'    => 'Description Type Must be String Type!',
            'cash_in.integer'       => 'Cash In Must be Integer Type!',
            'cash_out.integer'      => 'Cash Out Must be Integer Type!',
            'note.string'           => 'Note Must be string type!'
        ];
    }


}
