<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateMainProductRequest extends Request
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
            'name'=>'required',
            'description'=>'required',
            'unit_id'=>'required',
            'family_id'=>'required',
            'category_id'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'*required',
            'description.required'=>'*required',
            'unit_id.required'=>'*required',
            'family_id.required'=>'*required',
            'category_id.required'=>'*required'
        ];
    }
}
