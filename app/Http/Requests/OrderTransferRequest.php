<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderTransferRequest extends FormRequest
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
         'items_id' => 'required|array',
         'items_id.*' => 'required|numeric',
         'current_quantities' => 'required|array',
         'current_quantities.*' => 'required|numeric',
         'voucher_narration' => 'nullable|max:6000',
         'wa_narration' => 'nullable|max:6000',
         'payment_date' => 'required|date',
         'payment_amount' => 'required|numeric',
         'gst_amount' => 'required|numeric',
         'extra_charges' => 'required|numeric',

         'transport_id' => 'nullable|integer',
         'bilty_number' => 'nullable|max:150',
         'vehicle_number' => 'nullable|max:150',
         'transport_date' => 'nullable|date',
        ];
    }
}
