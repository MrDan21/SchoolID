@extends('layouts.auth')

@section('content')
<div class="card card-md shadow-sm">
    <div class="card-body">
        <h2 class="h2 text-center mb-4">{{ __('Verifica tu Correo Electrónico') }}</h2>

        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
            </div>
        @endif

        <p class="text-secondary text-center">
            {{ __('Antes de continuar, por favor revisa tu correo electrónico para un enlace de verificación.') }}<br>
            {{ __('Si no recibiste el correo') }},
        </p>

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">{{ __('haz clic aquí para solicitar otro') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
