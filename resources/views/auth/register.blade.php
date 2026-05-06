@extends('layouts.auth')

@section('content')
<div class="card card-md shadow-sm">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('Registrarse') }}</h2>
        <form method="POST" action="{{ route('register') }}" autocomplete="off" novalidate>
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Nombre') }}</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Ingresa tu nombre">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Ingresa tu correo">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                <div class="input-group input-group-flat">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Contraseña">
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
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Contraseña">
                    <span class="input-group-text">
                        <a href="#" class="link-secondary" title="{{ __('Mostrar contraseña') }}" data-bs-toggle="tooltip" id="toggle-password-confirm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                        </a>
                    </span>
                </div>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Registrarse') }}</button>
            </div>
        </form>
    </div>
</div>
<div class="text-center text-secondary mt-3">
    ¿Ya tienes cuenta? <a href="{{ route('login') }}" tabindex="-1">Inicia sesión</a>
</div>
</div>

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
@endsection
