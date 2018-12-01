<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreChartAccountRequest extends Request
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
            'name'=>'required|unique:chart_accounts,name',
            'account_number'=>'required|unique:chart_accounts,account_number',
            'description'=>'required',
        ];
    }
}
