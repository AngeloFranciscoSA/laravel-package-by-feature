<?php

namespace App\Modules\Car\Interfaces\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowCarRequests extends FormRequest
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    public function rules(): array
    {
        return [
            'id' => ['numeric'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->route()->parameters());
    }

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => __('messages.validation_error') ,
            'errors' => $validator->errors()
        ], self::HTTP_UNPROCESSABLE_ENTITY));
    }
}
