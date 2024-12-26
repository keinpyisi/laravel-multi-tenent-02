<?php

namespace App\Http\Requests\Auth;

use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('login_id', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login_id' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

       /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate_tenant(): void
    {
        $this->ensureIsNotRateLimited();

        if (Auth::guard('tenants')->attempt($this->only('login_id', 'password'), $this->boolean('remember'))) {
            $user = Auth::guard('tenants')->user();
            
            $session = $this->session();
            
            // Store authentication data
            $session->put('tenant_user_id', $user->id);
            $session->put('tenant_auth', true);
            $session->put('tenant_user', $user->only(['id', 'login_id', 'tenant_id']));
            
            // Force save session
            $session->save();

            // log_message('Authentication Success', [
            //     'session_id' => $session->getId(),
            //     'user_id' => $user->id,
            //     'session_data' => $session->all()
            // ]);

            RateLimiter::clear($this->throttleKey());
            return;
        }

        RateLimiter::hit($this->throttleKey());
        throw ValidationException::withMessages([
            'login_id' => trans('auth.failed'),
        ]);
    }



    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login_id' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login_id')).'|'.$this->ip());
    }
}
