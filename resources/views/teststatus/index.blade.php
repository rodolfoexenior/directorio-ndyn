@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Página de Prueba de Status Flash') }}
                </div>
                <div class="card-body">
                    
                    <h3 class="card-title mb-4">
                        Haz clic en el botón para redirigir al Dashboard y probar el mensaje "PROBANDOOO".
                    </h3>

                    <form method="POST" action="{{ route('test.status.trigger') }}">
                        @csrf 

                        <button type="submit" class="btn btn-danger btn-lg">
                            {{ __('Probar Redirección con Status') }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection