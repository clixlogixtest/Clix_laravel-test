<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required', 
            'surname' => 'required', 
            'email' => 'required|email|unique:users', 
            'contact_number' => 'required',
            'role' => 'required',
            'date_of_birth' => 'required|date|before:2002-05-21', 
            'address' => 'required', 
            'town' => 'required', 
            'post_code' => 'required', 
            'status' => 'required', 
        ];
    }

   
}
