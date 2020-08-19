<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeRequest extends FormRequest
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
            'question' => 'required|unique:challenges',
            'answer.*' => 'required'
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'answer.*.required' => 'A answer is required',
        ];
    }
}
