<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreAssetRequest extends Request
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
            'code'=>'required|unique:assets,code',
            'name'=>'required',
            'date_purchase'=>'required',
            'amount'=>'required',
            'residual_value'=>'required',
            'periode'=>'required|integer',
            'asset_account'=>'required',
            'biaya_penyusutan_account'=>'required',
            'akumulasi_penyusutan_account'=>'required'
        ];
    }
}
