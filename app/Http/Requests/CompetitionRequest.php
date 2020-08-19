<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionRequest extends FormRequest
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

        $end = date('d/m/Y', strtotime('+4 weeks'));

        $rules = [
            //'competition_title' => 'required|unique:competitions',
            'prize' => 'required',
            'ticket_price' => 'required',
            'available_ticket' => 'required',
            'closed_date' => 'required|date_format:d/m/Y|after_or_equal:'.$end
        ];
        return $rules;
    }
}
