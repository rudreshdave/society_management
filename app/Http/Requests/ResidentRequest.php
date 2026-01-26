<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResidentRequest extends FormRequest
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
        return [
            'residency_type'    => 'required|in:1,2,3',

            'wing_no'           => 'nullable|string|max:10',
            'floor_no'          => 'nullable|string|max:10',
            'flat_no'           => 'nullable|string|max:20',
            'bunglow_no'        => 'nullable|string|max:20',

            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'mobile'            => 'required|digits:10|unique:users,mobile',
            'alternate_mobile'  => 'nullable|digits:10',

            'move_in_date'      => 'required|date',
            'emergency_contact' => 'required|digits:10',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // Flat / Apartment
            if ($this->residency_type == 1) {
                if (!$this->wing_no) {
                    $validator->errors()->add('wing_no', 'Wing number is required');
                }
                if (!$this->floor_no) {
                    $validator->errors()->add('floor_no', 'Floor number is required');
                }
                if (!$this->flat_no) {
                    $validator->errors()->add('flat_no', 'Flat number is required');
                }
            }

            // Bungalow / Row House
            if (in_array($this->residency_type, [2, 3])) {
                if (!$this->bunglow_no) {
                    $validator->errors()->add('bunglow_no', 'Bungalow number is required');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'residency_type.required' => 'Residency type is required',
            'residency_type.in'       => 'Invalid residency type selected',

            'email.unique'            => 'Email already exists',
            'mobile.unique'           => 'Mobile number already exists',

            'mobile.digits'           => 'Mobile number must be 10 digits',
            'emergency_contact.digits' => 'Emergency contact must be 10 digits',
        ];
    }
}
