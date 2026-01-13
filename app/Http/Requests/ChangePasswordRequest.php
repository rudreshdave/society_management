<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    use ApiResponse;
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
        return [
            'current_password' => 'required',
            'new_password' => 'required|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*_-])[a-zA-Z0-9!@#$%&*_-].{7,}+$/',
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Please provide token',
            'new_password.required' => 'Please provide Password',
            'new_password.regex' => 'Password must contain  at least one lower case letter, one upper case letter, one digit, one special character and minimum 8 characters',
            'con_password.required' => 'Please provide Confirm Password',
            'con_password.same' => 'Password and Confirm Password does not match',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! Hash::check($this->current_password, $this->user()->password)) {
                $validator->errors()->add('current_password', 'The current password is incorrect.');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->customResponse(9, null, $validator->errors()->all()));
    }
}
