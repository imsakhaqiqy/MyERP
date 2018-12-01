<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateBankRequest extends Request
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
            'code'=>'required|max:7|unique:banks,code,'.$this->route('bank'),
            'name'=>'required',
            'account_name'=>'required',
            'account_number'=>'required',
            'value'=>'required'
        ];
    }
}
