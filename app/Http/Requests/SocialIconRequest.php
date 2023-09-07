<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialIconRequest extends FormRequest
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
            'facebook'      => 'required|string',
            'twitter'       => 'nullable|string',
            'instagram'     => 'nullable|string',
            'linkedin'      => 'nullable|string',
            'fb_messenger'  => 'nullable|string',
            'whatsapp'      => 'nullable|string',
            'is_active'     => 'required',
        ];
    }


    public function messages()
    {
        return [
            'facebook.required'     => 'Facebook link is required!',
            'facebook.string'       => 'Facebook link is must be String!',
            'twitter.string'        => 'Twitter link is must be String!',
            'instagram.string'      => 'Instagram link is must be String!',
            'linkedin.string'       => 'Linkedin link is must be String!',
            'fb_messenger.string'   => 'Messenger link is must be String!',
            'whatsapp.string'       => 'WhatsApp link is link must be String!',
            'is_active.required'    => 'Please link is select the tax status!',
        ];
    }


}
