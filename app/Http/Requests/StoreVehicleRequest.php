<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreVehicleRequest extends Request
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
            'code'=>'required|max:7|unique:vehicles,code',
            'vehicle_category'=>'required',
            'number_of_vehicle'=>'required',
        ];
    }
}
