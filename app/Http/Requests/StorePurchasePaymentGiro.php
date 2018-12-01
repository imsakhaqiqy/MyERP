<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StorePurchasePaymentGiro extends Request
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
          'purchase_order_invoice_id'=>'required|integer',
          'no_giro'=>'required',
          'nama_bank'=>'required',
          'tanggal_cair'=>'required',
          'amount_giro'=>'required',
          'gir_account'=>'required',
      ];
    }
}
