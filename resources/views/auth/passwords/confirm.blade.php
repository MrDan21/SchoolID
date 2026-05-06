@extends('layouts.auth')

@section('content')
<div class="card card-md shadow-sm">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('Confirmar Contraseña') }}</h2>

        <p class="text-secondary text-center mb-4">
            {{ __('Por favor confirma tu contraseña antes de continuar.') }}
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Contraseña') }}</label>
                <div class="input-group input-group-flat">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
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

            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Confirmar Contraseña') }}
                </button>

                @if (Route::has('password.request'))
                    <div class="mt-3 text-center">
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

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
