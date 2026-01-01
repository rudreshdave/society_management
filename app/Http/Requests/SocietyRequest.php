<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocietyRequest extends FormRequest
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
        $id = $this->route('id'); // society id (update)

        return [
            'society_name'     => 'required|string|max:255',
            'registration_no'  => 'required|string|max:255',
            'address_line_1'   => 'required|string|max:255',
            'address_line_2'   => 'nullable|string|max:255',
            'state_id'         => 'required|exists:states,id',
            'city_id'          => 'required|exists:cities,id',
            'pincode'          => 'required|digits:6',
            'contact_email'    => 'required|email|max:255',
            'contact_mobile'   => 'required|digits:10',
            'total_wings'      => 'nullable|integer|min:0',
            'total_flats'      => 'nullable|integer|min:0',

            // Multiple images
            'logos'            => 'nullable|array',
            'logos.*'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'society_name.required' => 'Society name is required',
            'registration_no.required' => 'Registration number is required',
            'state_id.required' => 'Please select state',
            'city_id.required' => 'Please select city',
            'pincode.digits' => 'Pincode must be 6 digits',
            'contact_mobile.digits' => 'Mobile number must be 10 digits',
            'logos.*.image' => 'Logo must be an image file',
        ];
    }
}
