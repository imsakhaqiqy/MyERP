<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StorePurchaseOrderInvoiceRequest extends Request
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
            'code'=>'required|unique:purchase_order_invoices,code',
            //'payment_method_id'=>'required|integer|exists:payment_methods,id',
            'bill_price'=>'required',
            'purchase_order_id'=>'required|integer|exists:purchase_orders,id',
            'term'=>'required'
        ];
    }
}
