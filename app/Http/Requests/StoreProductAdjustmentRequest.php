<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreProductAdjustmentRequest extends Request
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
            'adjust_no'=>'required',
            'in_out'=>'required',
            'adjust_account'=>'required',
        ];
    }
}
