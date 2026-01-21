<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NegocioController;
use App\Http\Controllers\TestStatusController;
use App\Http\Controllers\Admin\CaracteristicaController;
use App\Http\Controllers\UserController;           
use App\Http\Controllers\RoleController;     
use App\Http\Controllers\ProductoController;      
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\Admin\AdminNegocioController;
use App\Http\Controllers\Front\FrontController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/buscar', [FrontController::class, 'buscar'])->name('front.buscar');

// Rutas protegidas por el Middleware 'auth' y 'verified' (Dashboard)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// INICIO: GRUPO DE RUTAS PROTEGIDAS POR AUTH (NEGOCIOS Y PERFIL)
Route::middleware('auth')->group(function () {
    
    // Rutas de Perfil (Breeze estándar)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Negocios
    // 1. Rutas específicas para Completar el formulario
    Route::get('/negocios/{negocio}/completar', [NegocioController::class, 'completar'])->name('negocios.completar');
    Route::patch('/negocios/{negocio}/guardar-completar', [NegocioController::class, 'guardarCompletar'])->name('negocios.guardar-completar');
    
    // 2. Rutas estándar del recurso (index, create, store, show, edit, update, destroy)
    Route::resource('negocios', NegocioController::class);

    // Rutas de Productos (Recurso anidado bajo Negocios)
    // Esto generará rutas como: /negocios/{negocio}/productos/{producto}
    Route::resource('negocios.productos', ProductoController::class);
    Route::resource('negocios.servicios', ServicioController::class);
    // ...

// Rutas de Productos (Recurso anidado bajo Negocios)
Route::resource('negocios.productos', ProductoController::class);

// Rutas de Servicios (Recurso anidado bajo Negocios)
Route::resource('negocios.servicios', ServicioController::class);

// NUEVA RUTA: Desvincular/Eliminar una imagen de la galería de un servicio
Route::delete('negocios/{negocio}/servicios/{servicio}/imagen/{imagen}', [ServicioController::class, 'detachImage'])
    ->name('negocios.servicios.imagen.destroy');

// RUTA DE ELIMINACIÓN PERMANENTE DE LA GALERÍA CENTRAL
Route::delete('negocios/{negocio}/galeria/imagen/{imagen}', [ServicioController::class, 'destroyImage'])
    ->name('negocios.galeria.destroy');
    
// ...
    

    // ======================================================================
    // INICIO: RUTAS DE ADMINISTRACIÓN (PROTEGIDAS POR EL MIDDLEWARE 'admin')
    // ======================================================================
    Route::middleware('admin')->prefix('admin')->group(function () {

        // 1. CRUD de Características (Adenda 2)
        // Rutas: /admin/caracteristicas, /admin/caracteristicas/create, etc.
        // Nombres: caracteristicas.index, caracteristicas.create, etc.
        Route::resource('caracteristicas', CaracteristicaController::class);

        // 2. Ruta de Listado de TODOS los Negocios (Resuelve el enlace del dashboard)
        // codigo antiguo de negocios Route::get('negocios', [NegocioController::class, 'indexAdmin'])->name('admin.negocios.index');
        Route::get('negocios', [AdminNegocioController::class, 'index'])->name('admin.negocios.index');
        Route::get('negocios/{negocio}', [AdminNegocioController::class, 'show'])->name('admin.negocios.show');
        
        // 3. Ruta de Listado de Usuarios (Resuelve el enlace del dashboard)
        Route::get('users', [UserController::class, 'index'])->name('admin.users.index'); 
        
        // 4. Ruta de Roles (Resuelve el enlace del dashboard)
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index'); 

        // 5. otras rutas de administración según se necesiten...
        Route::patch('negocios/{negocio}/actualizar-estado', [AdminNegocioController::class, 'updateEstado'])->name('admin.negocios.updateEstado');
        

    });
    // ======================================================================
    // FIN: RUTAS DE ADMINISTRACIÓN
    // ======================================================================

});
// FIN: GRUPO DE RUTAS PROTEGIDAS POR AUTH (NEGOCIOS Y PERFIL)


require __DIR__.'/auth.php';