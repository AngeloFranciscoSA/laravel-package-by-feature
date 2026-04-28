<?php

declare(strict_types=1);

namespace App\Modules\Auth\Interfaces\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginAuthRequest extends FormRequest
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    public function rules(): array
    {
        if ($this->isMethod('GET')) {
            return [];
        }

        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation error.',
            'errors' => $validator->errors(),
        ], self::HTTP_UNPROCESSABLE_ENTITY));
    }
}
