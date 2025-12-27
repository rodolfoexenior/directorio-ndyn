<section>
    <header class="mb-3">
        <h5 class="card-title text-danger">{{ __('Eliminar Cuenta') }}</h5>
        <p class="card-subtitle text-muted">{{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Por favor, descarga cualquier dato o información que desees conservar.') }}</p>
    </header>

    <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Delete Account') }}
    </button>
    
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="confirmUserDeletionLabel">{{ __('Are you sure you want to delete your account?') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p class="text-muted">{{ __('Por favor, introduce tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.') }}</p>
                        
                        {{-- Campo de Contraseña para Confirmación --}}
                        <div class="mb-3">
                            <label for="password_confirm_delete" class="form-label visually-hidden">{{ __('Password') }}</label>
                            <input id="password_confirm_delete" name="password" type="password" class="form-control" placeholder="{{ __('Password') }}">
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>