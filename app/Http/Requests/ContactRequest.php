<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $nameRule = [
            'required', 'string', 'max:50',
            Rule::unique('contacts')->where(fn ($query) => $query->where('first_name', request()->input('first_name'))
                ->where('last_name', request()->input('last_name')))
        ];
        $rules = [
            'first_name' => $nameRule,
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules = array_merge($rules, [
                'first_name' => 'required|string|max:50',
                'email' => 'required|email',
            ]);
        }
        return $rules;
    }
    public function messages()
    {
        return [
            'first_name.unique' => '"' . request()->input('first_name') . ' ' . request()->input('last_name') . '"' . ' this name has already been taken.',
        ];
    }
    public function attributes()
    {
        return [
            'company_id' => "company"
        ];
    }
}
