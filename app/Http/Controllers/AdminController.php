<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Solo llegará aquí si tiene el middleware 'role:admin'
        return Inertia::render('Admin/Dashboard');
    }

    public function usuarios(Request $request)
    {
        // Obtener todos los usuarios con sus roles
        $usuarios = \App\Models\User::with('roles')->get();

        return Inertia::render('Admin/Usuarios', [
            'usuarios' => $usuarios
        ]);
    }
}
