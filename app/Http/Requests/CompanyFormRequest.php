<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyFormRequest extends FormRequest
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
            'name' => ['required', 'max:60',Rule::unique('companies')->ignore(optional($this->company)->id)],
            'category_id' => ['required', "exists:categories,id"],
            'email' => ['required', 'email', Rule::unique('companies')->ignore(optional($this->company)->id)],
            'phone' => ['nullable', Rule::unique('companies')->ignore(optional($this->company)->id)],
            'whatsapp' => ['nullable', Rule::unique('companies')->ignore(optional($this->company)->id)],
            'facebook' => ['nullable', 'url', Rule::unique('companies')->ignore(optional($this->company)->id)],
            'instagram' => ['nullable', 'url', Rule::unique('companies')->ignore(optional($this->company)->id)],
            'youtube' => ['nullable', 'url', Rule::unique('companies')->ignore(optional($this->company)->id)],
        ];
    }
}
