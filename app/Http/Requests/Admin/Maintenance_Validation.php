<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class Maintenance_Validation extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'allow_ip' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $ips = preg_split('/\s+/', trim($value));
                    foreach ($ips as $ip) {
                        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                            $fail("The IP address '$ip' is not valid.");
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Get the custom error messages for the validator.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'allow_ip.required' => 'An IP address is required.',
            'allow_ip.ip' => 'The IP address must be valid.',
            'maintenance_term.required' => 'The maintenance term is required.',
            'maintenance_term.string' => 'The maintenance term must be a string.',
        ];
    }

    /**
     * Prepare the data for validation, if needed.
     *
     * @return array
     */
    public function prepareForValidation(): array {
        return $this->all();
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator) {
        Log::error('Validation failed:', $validator->errors()->toArray());
        throw new ValidationException($validator);
    }
}
