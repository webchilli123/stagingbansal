<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PartyRequest extends FormRequest
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
            'name' => 'required|max:80|'.Rule::unique('parties', 'name')->ignore($this->party),
            'type' => 'required|string|max:3',
            'opening_balance' => 'sometimes|required|integer',
            'city_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'address' => 'nullable|max:200',
            'phone' => 'nullable|max:50',
            'mobile' => 'nullable|max:50',
            'fax' => 'nullable|max:50',
            'tin_number' => 'nullable|max:50',
            'url' =>'nullable|max:50',
            'email' => 'nullable|max:100',
            'note' => 'nullable|max:6000',
            'user_id' => 'required|integer'
        ];
    }
}
