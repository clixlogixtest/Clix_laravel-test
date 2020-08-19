<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ticketRequest extends FormRequest
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
            'competition' => 'required',
            //'Challenge' => 'required',
            'player' => 'required',
            'challenge_answer' => 'required'
        ];
        return $rules;
    }
}
