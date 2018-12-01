<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreSalesOrderRequest extends Request
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
            'customer_id'=>'required|integer',
            'product_id'=>'required|exists:products,id',
            'driver_id'=>'required|integer',
            'vehicle_id'=>'required|integer',
            'ship_date'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required'=>'Please select customer',
            'driver_id.required'=>'Please select driver',
            'vehicle_id.required'=>'Please select vehicle',
            'ship_date.required'=>'Please select ship date',
            'product_id.required'=>'Please select product',
        ];
    }
}
