<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateCashRequest extends Request
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
            'code'=>'required|max:7|unique:cashs,code,'.$this->route('cash'),
            'name'=>'required',
            'value'=>'required'
        ];
    }
}
