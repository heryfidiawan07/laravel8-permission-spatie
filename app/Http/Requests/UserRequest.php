<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', Rule::unique('users')->ignore($this->user)],
            'email' => ['required|email', Rule::unique('users')->ignore($this->user)],
            'roles' => 'array|required',
        ];

        if ($this->method() == 'POST') {
            $rules['password'] = 'required|confirmed|min:6';
        }

        return $rules;
    }

    // public function messages()
    // {
    //     return [
    //         'name.required' => 'Nama tidak boleh kosong.',
    //     ];
    // }
}
