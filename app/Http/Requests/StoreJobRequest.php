<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
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
            'contract_id' => ['required', 'exists:contracts,id'],
            'title' => ['required'],
            'description' => ['required'],
            'apply_link' => ['required', 'url'],
            'tags' => ['nullable'],
            'position' => ['required'],
            'location' => ['required'],
            'remote_working' => ['required'],
            'salary' => ['required'],
            'working_day' => ['required', Rule::in('full-time', 'part-time')],
            'company' => ['required'],
            'logo' => ['nullable', 'file', 'mimes:jpg,png,jpeg', 'max:5000']
        ];
    }
}