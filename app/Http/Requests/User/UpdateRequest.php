<?php

namespace App\Http\Requests\User;

use App\Services\ResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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

    public function failedValidation(Validator $validator): void
    {
        abort(ResponseService::failed('Invalid request', $validator->errors()->toArray(), 422));
    }
}
