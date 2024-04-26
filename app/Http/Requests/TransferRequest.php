<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'receiver_id' => 'required|integer',
            'narration' => 'nullable',
            'order_id' => 'nullable|integer',
            'process_id' => 'required|integer',

            'send_items_id' =>  'required|array',
            'send_items_id.*' => 'required|numeric',

            'send_quantities' => 'required|array',
            'send_quantities.*' => 'required|numeric',
                
        ];
    }
}
