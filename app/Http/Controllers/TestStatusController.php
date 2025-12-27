<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestStatusController extends Controller
{
    /**
     * Muestra la página con el botón "Probar".
     */
    public function showTestPage()
    {
        return view('teststatus.index'); // La nueva vista
    }

    /**
     * Redirige al dashboard con el mensaje de estado específico.
     */
    public function triggerStatus()
    {
        return redirect()->route('dashboard')
            ->with('status', 'PROBANDOOO: El mensaje de estado flash funciona correctamente.');
    }
}