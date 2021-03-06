<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateProductRequest extends Request
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
            //'code'=>'required|unique:products,code,'.$this->route('product'),
            'name'=>'required|unique:products,name,'.$this->route('product'),
            'category_id'=>'required|exists:categories,id',
            'family_id'=>'required|exists:families,id',
            'unit_id'=>'required|exists:units,id',
            'image'=>'mimes:jpg,png,jpeg',
            'stock'=>'integer',
            'minimum_stock'=>'integer',
        ];
    }
}
