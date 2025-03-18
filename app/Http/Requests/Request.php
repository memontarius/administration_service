<?php

namespace App\Http\Requests;

use App\Services\ResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function failedValidation(Validator $validator): void
    {
        ResponseService::abortAsInvalidRequest($validator->errors()->toArray());
    }
}
