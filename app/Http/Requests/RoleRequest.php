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
        // dd($this->role_permission);
        $rules = [
            'name' => ['required', Rule::unique('roles')->ignore($this->role_permission)],
            'permissions' => 'array|required',
        ];

        return $rules;
    }

    // public function messages()
    // {
    //     return [
    //         'name.required' => 'Nama tidak boleh kosong.',
    //     ];
    // }
}
