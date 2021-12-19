<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the role is authorized to make this request.
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
            'name' => 'required|unique:roles',
        ];

        $rules['permissions'] = 'array|required';

        if ($this->method() == 'PUT') {
            $rules['name'] = ['required', Rule::unique('roles', 'name')->ignore($this->role)];
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
