<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocietyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     * 🔥 This fixes "city_id = undefined"
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'city_id' => $this->city_id === 'undefined' ? null : $this->city_id,
            'state_id' => $this->state_id === 'undefined' ? null : $this->state_id,
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('societies'); // for update route-model binding (optional)

        return [
            'society_name'     => 'required|string|max:255',
            'registration_no'  => 'required|string|max:255',
            'address_line_1'   => 'required|string|max:255',
            'address_line_2'   => 'nullable|string|max:255',
            'state_id'         => 'required|integer|exists:states,id',
            'city_id'          => 'required|integer|exists:cities,id',
            'pincode'          => 'required|digits:6',
            'contact_email'    => 'required|email',
            'contact_mobile'   => 'required|digits:10',
            'total_wings'      => 'nullable|integer|min:0',
            'total_flats'      => 'nullable|integer|min:0',
            'logos'            => 'nullable|array',
            'logos.*'          => 'image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'society_name.required'    => 'Society name is required.',
            'registration_no.required' => 'Registration number is required.',
            'address_line_1.required'  => 'Address is required.',
            'state_id.required'        => 'Please select a state.',
            'state_id.exists'          => 'Selected state is invalid.',
            'city_id.required'         => 'Please select a city.',
            'city_id.exists'           => 'Selected city is invalid.',
            'pincode.digits'           => 'Pincode must be exactly 6 digits.',
            'contact_mobile.digits'    => 'Mobile number must be exactly 10 digits.',
            'logos.*.image'            => 'Each logo must be an image file.',
            'logos.*.max'              => 'Each logo must not exceed 2MB.',
        ];
    }
}
