<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'Les deux mots de passe ne correspondent pas.',
            'password.min'       => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Le nouveau mot de passe doit être différent de l\'ancien.']);
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        $route = ($user->role === 'employe') ? 'employe.profil' : 'dashboard';

        return redirect()->route($route)
            ->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
