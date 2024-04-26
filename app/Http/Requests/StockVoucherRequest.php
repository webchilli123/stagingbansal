<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockVoucherRequest extends FormRequest
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
            'dr_party' => 'required|integer',
            'cr_party' => 'required|integer',
            'amount' => 'required|numeric',
            'rate' => 'required|numeric',
            'item_id' => 'sometimes|required|integer',
            'narration' => 'nullable|max:6000',
        ];
    }
}
