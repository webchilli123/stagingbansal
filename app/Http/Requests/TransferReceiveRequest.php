<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferReceiveRequest extends FormRequest
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
            'transfer_date' => 'required|date',
            'sender_id' => 'required|integer',
            'narration' => 'nullable|max:6000',
            // 'order_id' => 'nullable|integer',
            // 'process_id' => 'required|integer',
            
            'items_id' =>   'required|array',
            'items_id.*' => 'required|integer',
            
            'receive_item_id' => 'required|array',
            'receive_item_id.*' => 'required|integer',

            'receive_quantities' => 'required|array',
            'receive_quantities.*' => 'required|numeric',

            'receive_quantities_with_waste' => 'required|array',
            'receive_quantities_with_waste.*' => 'required|numeric',
            
            'wastes' => 'required|array',
            'wastes.*' => 'required|numeric',

            'rates' => 'required|array',
            'rates.*' => 'required|numeric',                

        ];
    }
}
