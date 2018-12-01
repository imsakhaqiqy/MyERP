<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreMainProductRequest extends Request
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
            'code'=>'required',
            'mulai_dari'=>'required',
            'sebanyak'=>'required',
            'deskripsi'=>'required',
            'unit_id'=>'required',
            'family_id'=>'required',
            'category_id'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'code.required'=>'*required',
            'mulai_dari.required'=>'*required',
            'sebanyak.required'=>'*required',
            'deskripsi.required'=>'*required',
            'unit_id.required'=>'*required',
            'family_id.required'=>'*required',
            'category_id.required'=>'*required'
        ];
    }
}
