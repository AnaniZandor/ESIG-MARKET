<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ✅ LIMITATION DES TENTATIVES (5 max par minute)
        $rateLimiter = app(RateLimiter::class);
        $key = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());
        
        // Vérifier si trop de tentatives
        if ($rateLimiter->tooManyAttempts($key, 5)) {
            $seconds = $rateLimiter->availableIn($key);
            
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives de connexion. Réessayez dans {$seconds} secondes.",
            ])->status(429);
        }
        
        try {
            $request->authenticate();
        } catch (\Exception $e) {
            // Échec de connexion : compter la tentative
            $rateLimiter->hit($key, 60);
            throw $e;
        }
        
        // Succès : effacer les tentatives
        $rateLimiter->clear($key);
        
        $request->session()->regenerate();

        // 🔥 récup user connecté
        $user = Auth::user();

        // 👮 ADMIN
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 👤 USER / VENDEUR / ACHETEUR
        return redirect()->route('articles.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
?>