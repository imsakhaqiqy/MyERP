<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateSupplierRequest extends Request
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
            'code'=>'required|unique:suppliers,code,'.$this->route('supplier'),
            'name'=>'required',
            'address'=>'required',
            // 'pic_name'=>'required',
            // 'primary_email'=>'email',
            // 'primary_phone_number'=>'required',
        ];
    }
}
