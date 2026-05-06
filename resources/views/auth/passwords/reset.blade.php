@extends('layouts.auth')

@section('content')
<div class="card card-md shadow-sm">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('Restablecer Contraseña') }}</h2>

        <form method="POST" action="{{ route('password.update') }}" autocomplete="off" novalidate>
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                <div class="input-group input-group-flat">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="{{ __('Mostrar contraseña') }}" data-bs-toggle="tooltip" id="toggle-password">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        </a>
                    </span>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password-confirm" class="form-label">{{ __('Confirmar Contraseña') }}</label>
                <div class="input-group input-group-flat">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="{{ __('Mostrar contraseña') }}" data-bs-toggle="tooltip" id="toggle-password-confirm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        </a>
                    </span>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Restablecer Contraseña') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function setupToggle(toggleId, inputId) {
            const toggleBtn = document.getElementById(toggleId);
            const inputField = document.getElementById(inputId);

            if (toggleBtn && inputField) {
                toggleBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    if (inputField.type === "password") {
                        inputField.type = "text";
                        toggleBtn.setAttribute("data-bs-original-title", "{{ __('Ocultar contraseña') }}");
                    } else {
                        inputField.type = "password";
                        toggleBtn.setAttribute("data-bs-original-title", "{{ __('Mostrar contraseña') }}");
                    }
                    
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        const tooltipInstance = bootstrap.Tooltip.getInstance(toggleBtn);
                        if (tooltipInstance) {
                            tooltipInstance.setContent({ '.tooltip-inner': toggleBtn.getAttribute('data-bs-original-title') });
                        }
                    }
                });
            }
        }

        setupToggle("toggle-password", "password");
        setupToggle("toggle-password-confirm", "password-confirm");
    });
</script>
