<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // --- INICIO DE CÓDIGO NUEVO PARA ASIGNACIÓN DE ROL ---

        // 1. Buscar el ID del rol 'cliente_estandar'
        $clienteEstandarRole = DB::table('roles')->where('nombre', 'cliente_estandar')->first();

        if ($clienteEstandarRole) {
            // 2. Insertar la conexión en la tabla pivote 'role_user'
            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $clienteEstandarRole->id,
            ]);
        }

        // --- FIN DE CÓDIGO NUEVO ---


        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
