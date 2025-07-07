<?php

namespace App\Modules\Car\Interfaces\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditCarRequests extends FormRequest
{
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    public function rules(): array
    {
        return [
            'id' => ['required','numeric', 'min:1'],
            'brand' => ['required','string'],
            'model' => ['required','string'],
            'year' => ['required','numeric'],
            'color' => ['required','string'],
            'price' => ['required','string'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
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
