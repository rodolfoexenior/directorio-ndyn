@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4">
                {{ __('Perfil del Usuario') }}
            </h2>

            {{-- 1. Actualizar Información de Perfil --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    {{ __('Información del Perfil') }}
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 2. Actualizar Contraseña --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    {{ __('Actualizar Contraseña') }}
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- 3. Eliminar Cuenta --}}
            <div class="card shadow border-danger mb-4">
                <div class="card-header bg-danger text-white">
                    {{ __('Eliminar Cuenta') }}
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection