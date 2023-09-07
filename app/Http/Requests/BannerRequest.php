<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
            'name'          => 'nullable|string',
            'banner_image'  => 'required|string',
            'is_active'     => 'required',
        ];
    }


    public function messages()
    {
        return [
            'name.string'               => 'Banner title must be String Type!',
            'banner_image.required'     => 'Banner image is required!',
            'is_active.required'        => 'Please select the category status!',
        ];
    }


}
