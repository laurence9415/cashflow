<?php

namespace App\Http\Requests;

use App\Models\BusinessExpense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessExpenseRequest extends FormRequest
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
            'status' => ['required', Rule::in(BusinessExpense::statuses())],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string']
        ];
    }
}
