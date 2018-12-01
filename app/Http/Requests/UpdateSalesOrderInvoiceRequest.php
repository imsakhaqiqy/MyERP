<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateSalesOrderInvoiceRequest extends Request
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
            'bill_price'=>'required',
            // 'paid_price'=>'required',
            'sales_order_invoice_id'=>'required|integer|exists:sales_order_invoices,id',
        ];
    }
}
