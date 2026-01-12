<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
        $rules = [
            'mobile' => 'required'
        ];
        if ($this->input('logintype') == 1) {
            $rules['password'] = 'required|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*_-])[a-zA-Z0-9!@#$%&*_-].{7,}+$/';
        }
        return $rules;
    }


    public function messages(): array
    {
        return [
            'mobile.required' => 'The Mobile number field is required.',
            'mobile.min' => 'The Mobile number must be a valid.',
            'mobile.numeric' => 'The Mobile number must be a valid.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            // 'password.regex' => 'Password must contain  at least one lower case letter, one upper case letter, one digit, one special character and minimum 8 characters'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->customResponse(9, null, $validator->errors()));
    }
}
