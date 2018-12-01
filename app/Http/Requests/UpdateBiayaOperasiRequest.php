<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateBiayaOperasiRequest extends Request
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
            'pay_method'=>'required',
            'beban_operasi_account'=>'required',
            'cash_bank_account'=>'required',
            'debit'=>'required',
            'credit'=>'required'
            //'amount'=>'required'
        ];
    }
}
