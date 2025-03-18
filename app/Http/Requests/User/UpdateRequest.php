<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleTable = config('permission.table_names.roles');

        return [
            'name' => 'required|string',
            'last_name' => 'string',
            'login' => 'string|unique:users',
            'scopes.*' => "distinct|exists:$roleTable,name"
        ];
    }
}
