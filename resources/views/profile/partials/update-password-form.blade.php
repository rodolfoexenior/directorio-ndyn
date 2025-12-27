<section>
    <header class="mb-3">
        <h5 class="card-title">{{ __('Actualizar Contraseña') }}</h5>
        <p class="card-subtitle text-muted">{{ __('Asegúrate de que tu cuenta utiliza una contraseña larga y aleatoria para mantener la seguridad.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        {{-- Contraseña Actual --}}
        <div class="mb-3">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="current_password" name="current_password" type="password" 
                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                   required autocomplete="current-password">
            
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nueva Contraseña --}}
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input id="password" name="password" type="password" 
                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                   required autocomplete="new-password">

            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirmar Contraseña --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" 
                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                   required autocomplete="new-password">

            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botón de ENVÍO y Status --}}
        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="btn btn-primary me-3">{{ __('Save') }}</button> 

            @if (session('status') === 'password-updated')
                <p class="text-success small my-0">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>