<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StorePaymentInvoiceSalesKasRequest extends Request
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
            'cash_id'=>'required',
            'sum_amount'=>'required',
            'cash_account'=>'required',
        ];
    }
}
