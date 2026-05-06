@extends('layouts.auth')

@section('content')
<div class="card card-md shadow-sm">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('Iniciar Sesión') }}</h2>
        <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Correo Electrónico') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@correo.com">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-2">
                <label for="password" class="form-label">
                    {{ __('Contraseña') }}
                    @if (Route::has('password.request'))
                        <span class="form-label-description">
                            <a href="{{ route('password.request') }}">{{ __('¿Olvidaste tu contraseña?') }}</a>
                        </span>
                    @endif
                </label>
                <div class="input-group input-group-flat">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Tu contraseña">
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

            <div class="mb-2">
                <label class="form-check" for="remember">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="form-check-label">{{ __('Recordarme') }}</span>
                </label>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('Iniciar Sesión') }}</button>
            </div>
        </form>
    </div>
</div>
@if (Route::has('register'))
<div class="text-center text-secondary mt-3">
    ¿Aún no tienes cuenta? <a href="{{ route('register') }}" tabindex="-1">Regístrate</a>
</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.getElementById("toggle-password");
        const passwordInput = document.getElementById("password");

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener("click", function(e) {
                e.preventDefault();
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    togglePassword.setAttribute("data-bs-original-title", "{{ __('Ocultar contraseña') }}");
                } else {
                    passwordInput.type = "password";
                    togglePassword.setAttribute("data-bs-original-title", "{{ __('Mostrar contraseña') }}");
                }
                
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    const tooltipInstance = bootstrap.Tooltip.getInstance(togglePassword);
                    if (tooltipInstance) {
                        tooltipInstance.setContent({ '.tooltip-inner': togglePassword.getAttribute('data-bs-original-title') });
                    }
                }
            });
        }
    });
</script>
@endsection
