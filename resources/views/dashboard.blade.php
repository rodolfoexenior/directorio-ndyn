@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Mensajes de Estado --}}
            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Tarjeta de Bienvenida --}}
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    {{ __('Bienvenido a tu Panel de Control') }}
                </div>
                <div class="card-body">
                    {{ __("¡Estás conectado! Usa el menú superior o las tarjetas a continuación para administrar tu información.") }}
                </div>
            </div>

            {{-- ====================================================================== --}}
            {{-- SECCIÓN 1: GESTIÓN DE NEGOCIOS (PARA TODOS LOS USUARIOS) --}}
            {{-- ====================================================================== --}}
            <h4 class="mb-3 mt-4 text-secondary">{{ __('Gestión de Negocios') }}</h4>

            <div class="row">

                {{-- Tarjeta 1: Listar Mis Negocios --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100 shadow-sm border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">
                                <i class="bi bi-list-task"></i> {{ __('Ver Mis Negocios') }}
                            </h5>
                            <p class="card-text">
                                {{ __('Administra, edita y revisa el estado de tus negocios registrados.') }}</p>
                        </div>
                        <div class="card-footer bg-info text-white">
                            <a href="{{ route('negocios.index') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Ir al Listado') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 2: Registrar Nuevo Negocio --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100 shadow-sm border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success">
                                <i class="bi bi-plus-circle"></i> {{ __('Registrar Nuevo') }}
                            </h5>
                            <p class="card-text">{{ __('Añade un nuevo emprendimiento o negocio a la plataforma.') }}
                            </p>
                        </div>
                        <div class="card-footer bg-success text-white">
                            <a href="{{ route('negocios.create') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Nuevo Negocio') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 3: Configuración de Perfil --}}
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100 shadow-sm border-secondary">
                        <div class="card-body">
                            <h5 class="card-title text-secondary">
                                <i class="bi bi-person-gear"></i> {{ __('Configuración de Perfil') }}
                            </h5>
                            <p class="card-text">{{ __('Actualiza tu nombre, correo electrónico y contraseña.') }}</p>
                        </div>
                        <div class="card-footer bg-secondary text-white">
                            <a href="{{ route('profile.edit') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Editar Perfil') }}</a>
                        </div>
                    </div>
                </div>
            </div> {{-- Fin .row Gestión de Negocios --}}


            {{-- ====================================================================== --}}
            {{-- SECCIÓN 2: PANEL ADMINISTRATIVO (SOLO PARA ADMIN) --}}
            {{-- ====================================================================== --}}

            @if (Auth::user()->hasRole('admin_principal'))

            <h4 class="mb-3 mt-5 text-danger">{{ __('Panel de Administración') }}</h4>

            <div class="row">

                {{-- Tarjeta Admin 1: Ver TODOS los Negocios (PENDIENTE DE RUTA) --}}
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 shadow-sm border-danger">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><i class="bi bi-briefcase"></i>
                                {{ __('Todos los Negocios') }}</h5>
                            <p class="card-text small">
                                {{ __('Revisar y aprobar todos los negocios registrados por los usuarios.') }}</p>
                        </div>
                        <div class="card-footer bg-danger text-white">
                            <a href="{{ route('admin.negocios.index') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Gestionar') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Admin 2: Ver Características (CRUD) --}}
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 shadow-sm border-danger">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><i class="bi bi-tags"></i>
                                {{ __('Ver Características') }}</h5>
                            <p class="card-text small">
                                {{ __('Administrar el listado de atributos y servicios disponibles.') }}</p>
                        </div>
                        <div class="card-footer bg-danger text-white">
                            <a href="{{ route('caracteristicas.index') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Gestionar') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Admin 3: Ver Roles (CRUD) (PENDIENTE DE RUTA) --}}
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 shadow-sm border-danger">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><i class="bi bi-person-badge"></i> {{ __('Ver Roles') }}
                            </h5>
                            <p class="card-text small">{{ __('Administrar roles y sus descripciones.') }}</p>
                        </div>
                        <div class="card-footer bg-danger text-white">
                            <a href="{{ route('roles.index') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Gestionar') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta Admin 4: Ver Usuarios (PENDIENTE DE RUTA) --}}
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 shadow-sm border-danger">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><i class="bi bi-people"></i> {{ __('Ver Usuarios') }}
                            </h5>
                            <p class="card-text small">{{ __('Gestionar la lista de usuarios y asignarles roles.') }}
                            </p>
                        </div>
                        <div class="card-footer bg-danger text-white">
                            <a href="{{ route('admin.users.index') }}"
                                class="btn btn-sm btn-light w-100">{{ __('Gestionar') }}</a>
                        </div>
                    </div>
                </div>

            </div> {{-- Fin .row Panel Admin --}}

            @endif {{-- Fin @if Admin --}}


        </div> {{-- Fin .col-md-10 --}}
    </div> {{-- Fin .row justify-content-center --}}
</div> {{-- Fin .container py-4 --}}
@endsection