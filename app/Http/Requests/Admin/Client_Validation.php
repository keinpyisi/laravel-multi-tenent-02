<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class Client_Validation extends FormRequest {
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
            'client_name' => ['required', 'string', 'max:255'],
            'account_name' => ['required', 'string', 'max:255', 'unique:tenants,account_name'],
            'domain' => ['nullable', 'string', 'max:255', 'unique:tenants,domain'],
            'database' => ['nullable', 'string', 'max:255', 'unique:tenants,database'],
            'kana' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'genre' => ['nullable', 'string', 'max:255'],
            'person_in_charge' => ['nullable', 'string', 'max:255'],
            'tel' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'post_code' => ['nullable', 'string', 'max:10'],
            'fax_number' => ['nullable', 'string', 'max:15'],
            'e_mail' => ['nullable', 'email', 'max:255'],
            'homepage' => ['nullable', 'url', 'max:255'],
            'support_mail' => ['nullable', 'email', 'max:255'],
            'note' => ['nullable', 'string'],
            'login_id' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string',  'max:255', 'confirmed'],
        ];
    }

    /**
     * Get the custom error messages for the validator.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'client_name.required' => __('validation.required', ['attribute' => 'client_name']),
            'client_name.string' => __('validation.string', ['attribute' => 'client_name']),
            'client_name.max' => __('validation.max.string', ['attribute' => 'client_name', 'max' => 255]),
            'account_name.required' => __('validation.required', ['attribute' => 'account_name']),
            'account_name.string' => __('validation.string', ['attribute' => 'account_name']),
            'account_name.max' => __('validation.max.string', ['attribute' => 'account_name', 'max' => 255]),
            'account_name.unique' => __('validation.unique', ['attribute' => 'account_name']),
            'domain.string' => __('validation.string', ['attribute' => 'domain']),
            'domain.max' => __('validation.max.string', ['attribute' => 'domain', 'max' => 255]),
            'domain.unique' => __('validation.unique', ['attribute' => 'domain']),
            'database.string' => __('validation.string', ['attribute' => 'database']),
            'database.max' => __('validation.max.string', ['attribute' => 'database', 'max' => 255]),
            'database.unique' => __('validation.unique', ['attribute' => 'database']),
            'kana.required' => __('validation.required', ['attribute' => 'kana']),
            'kana.string' => __('validation.string', ['attribute' => 'kana']),
            'kana.max' => __('validation.max.string', ['attribute' => 'kana', 'max' => 255]),
            'logo.file' => __('validation.file', ['attribute' => 'logo']),
            'logo.image' => __('validation.image', ['attribute' => 'logo']),
            'logo.mimes' => __('validation.mimes', ['attribute' => 'logo']),
            'logo.max' => __('validation.max.file', ['attribute' => 'logo', 'max' => 2048]),
            'genre.string' => __('validation.string', ['attribute' => 'genre']),
            'genre.max' => __('validation.max.string', ['attribute' => 'genre', 'max' => 255]),
            'person_in_charge.string' => __('validation.string', ['attribute' => 'person_in_charge']),
            'person_in_charge.max' => __('validation.max.string', ['attribute' => 'person_in_charge', 'max' => 255]),
            'tel.string' => __('validation.string', ['attribute' => 'tel']),
            'tel.max' => __('validation.max.string', ['attribute' => 'tel', 'max' => 15]),
            'address.string' => __('validation.string', ['attribute' => 'address']),
            'address.max' => __('validation.max.string', ['attribute' => 'address', 'max' => 255]),
            'post_code.string' => __('validation.string', ['attribute' => 'post_code']),
            'post_code.max' => __('validation.max.string', ['attribute' => 'post_code', 'max' => 10]),
            'fax_number.string' => __('validation.string', ['attribute' => 'fax_number']),
            'fax_number.max' => __('validation.max.string', ['attribute' => 'fax_number', 'max' => 15]),
            'e_mail.email' => __('validation.email', ['attribute' => 'e_mail']),
            'e_mail.max' => __('validation.max.string', ['attribute' => 'e_mail', 'max' => 255]),
            'homepage.url' => __('validation.url', ['attribute' => 'homepage']),
            'homepage.max' => __('validation.max.string', ['attribute' => 'homepage', 'max' => 255]),
            'support_mail.email' => __('validation.email', ['attribute' => 'support_mail']),
            'support_mail.max' => __('validation.max.string', ['attribute' => 'support_mail', 'max' => 255]),
            'note.string' => __('validation.string', ['attribute' => 'note']),
            'login_id.required' => __('validation.required', ['attribute' => 'login_id']),
            'login_id.string' => __('validation.string', ['attribute' => 'login_id']),
            'user_name.required' => __('validation.required', ['attribute' => 'user_name']),
            'user_name.string' => __('validation.string', ['attribute' => 'user_name']),
            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.string' => __('validation.string', ['attribute' => 'password']),
            'password.confirmed' => __('validation.confirmed', ['attribute' => 'password']),
        ];
    }

    /**
     * Prepare the data for validation, if needed.
     *
     * @return array
     */
    public function prepareForValidation(): array {
        $this->merge([
            'insert_user_id' => 1,
            'update_user_id' => 1,
            'domain' => $this->account_name,
            'database' => $this->account_name,
        ]);

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
