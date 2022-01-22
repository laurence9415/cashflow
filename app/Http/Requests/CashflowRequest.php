<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashflowRequest extends FormRequest
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
            'forms' => ['required'],
            'forms.*.amount' => ['required', 'numeric'],
            'forms.*.description' => ['required', 'string'],
            'date' => ['required', 'date']
        ];
    }

    public function messages()
    {
        return [
            'forms.*.amount.required' => 'The amount field is required',
            'forms.*.description.required' => 'The description field is required',
        ];
    }
}
