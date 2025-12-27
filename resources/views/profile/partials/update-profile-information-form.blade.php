<section>
    <header class="mb-3">
        <h5 class="card-title">{{ __('Actualizar Perfil') }}</h5>
        <p class="card-subtitle text-muted">{{ __("Actualiza el nombre y la dirección de correo electrónico de tu cuenta.") }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        {{-- CAMPO NOMBRE --}}
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- CAMPO EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-muted">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">{{ __('Click here to re-send the verification email.') }}</button>
                    </p>
                </div>
            @endif
        </div>

        {{-- Botón de ENVÍO y Status --}}
        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="btn btn-primary me-3">{{ __('Save') }}</button> 

            @if (session('status') === 'profile-updated')
                <p class="text-success small my-0">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>