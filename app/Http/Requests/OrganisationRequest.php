<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganisationRequest extends FormRequest
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
            'organisation_name' => 'required|unique:organisations', 
            'uk_company_registration_number' => 'required', 
            'address' => 'required',
            'postcode' => 'required',
            'phone' => 'required', 
            'organisation_logo' => 'required|mimes:jpg,jpeg,png,bmp',
            'competition_website_url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'player_start_wallet_balance' => 'required',
            'placeholder_draw_video' => 'required',
            'paypal_api_credentials' => 'required',
            'terms_and_conditions' => 'required|mimes:html'
        ];
        return $rules;
    }
}
