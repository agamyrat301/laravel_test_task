<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'min:2', 'max:255'],
            'phone'   => ['required', 'string', 'regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/'],
            'email'   => ['required', 'string', 'email:rfc'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Name is required.',
            'name.min'         => 'Name must be at least 2 characters.',
            'name.max'         => 'Name cannot exceed 255 characters.',
            'phone.required'   => 'Phone number is required.',
            'phone.regex'      => 'Please provide a valid phone number (7–20 digits, may include +, spaces, dashes, parentheses).',
            'email.required'   => 'Email address is required.',
            'email.email'      => 'Please provide a valid email address.',
            'comment.required' => 'Comment is required.',
            'comment.min'      => 'Comment must be at least 10 characters.',
            'comment.max'      => 'Comment cannot exceed 2000 characters.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}
