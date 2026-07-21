<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. On récupère si la case "Se souvenir de moi" est cochée
        $remember = $request->has('remember');

        // 2. On passe la variable $remember en deuxième paramètre d'Auth::attempt
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            // Un employé est envoyé vers son espace personnel, les autres vers le back-office
            if ((Auth::user()->role ?? null) === 'employe') {
                return redirect()->route('employe.profil');
            }
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput($request->only('email', 'remember')); // Garder la case cochée si erreur
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,gestionnaire',
        ]);

        // Conserve parfaitement la structure avec le rôle que tu avais mis !
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Utilisateur créé avec succès !');
    }
}
