<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StorePurchaseReturnRequest extends Request
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
            'product_id'=>'required|array',
            'returned_quantity'=>'required|array',
        ];
    }

    public function messages(){
        return [
            'product_id.required' =>'Please select product',
            'returned_quantity.required' =>'Please input qty',
        ];
    }
}
