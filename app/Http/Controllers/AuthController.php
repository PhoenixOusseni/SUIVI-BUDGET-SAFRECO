<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle the user login request.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login_admin(Request $request)
    {
        // Valider les données reçues
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Tenter l'authentification
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // Protection contre fixation de session

            return redirect()->route('home')->with('success', 'Connexion réussie');
        }

        // Échec : identifiants invalides
        return back()
            ->withErrors(['email' => 'Les identifiants sont invalides.'])
            ->onlyInput('email');
    }

    /**
     * Handle the user logout request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Protection CSRF

        return redirect()->route('auth_admin')->with('success', 'Déconnexion réussie.');
    }
}
