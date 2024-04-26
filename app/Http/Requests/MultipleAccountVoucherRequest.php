<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MultipleAccountVoucherRequest extends FormRequest
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
            'transaction_date' => 'required|date',
            'account_id' => 'required|integer',
            'drcr' => 'required|string|max:2',

            'parties' => 'required|array',
            'parties.*' => 'required|integer',

            'amounts' => 'required|array',
            'amounts.*' => 'required|numeric',
            
            'narrations' => 'nullable|array',
            'narrations.*' => 'nullable|max:6000'
        ];
    }
}
