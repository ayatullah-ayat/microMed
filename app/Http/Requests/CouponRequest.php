<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'coupon_name'       => 'nullable|string',
            'discount_type'     => 'required|string',
            'coupon_code'       => 'required|string',
            'coupon_discount'   => 'required|integer', 
            'coupon_validity'   => 'nullable|string', 
            'status'            => 'required',
        ];
    }

    public function messages()
    {
        return [
            'coupon_name.string'        => 'Coupon Name Must Be String Type!',
            'discount_type.required'    => 'Discount Type is Required!',
            'coupon_code.required'      => 'Coupon Code Required!',
            'coupon_discount.required'  => 'Coupon Discount Required!',
            'coupon_validity.string'    => 'Coupon Validity must be String Type!',
            'status.required'           => 'Please select the tax status!',
        ];
    }


}
