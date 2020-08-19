<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrizeUpdateRequest extends FormRequest
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
        $rules = [
            'prize_name' => 'required', 
            'cash_value' => 'required', 
            'currency' => 'required',
            'category' => 'required',
            'description' => 'required'
           /* , 
            'available' => 'required'*/
        ];
        return $rules;
    }
}
