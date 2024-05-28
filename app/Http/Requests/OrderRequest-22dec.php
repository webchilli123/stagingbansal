<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'type' => 'required|max:50',
            // 'order_date' => 'required|date',
            'due_date' => 'required|date',
            'party_id' => 'required|integer',
            'narration' => 'nullable|max:6000',

            'items' =>  'required|array',
            'items.*' => 'required|integer|distinct',
            
            'quantities' => 'required|array',
            'quantities.*' => 'required|numeric',
            
            'rates' => '  required|array',
            'rates.*' => 'required|numeric',
            
            'total_prices' => '  required|array',
            'total_prices.*' => 'required|numeric',
        ];
    }
}
