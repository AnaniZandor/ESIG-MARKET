<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AFFICHER LE PROFIL
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $user     = auth()->user();
        $articles = Article::where('user_id', $user->id)
                           ->with(['images', 'category'])
                           ->latest()
                           ->get();

        return view('profile.index', compact('user', 'articles'));
    }

    /*
    |--------------------------------------------------------------------------
    | METTRE À JOUR LE PROFIL
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validation
        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'filiere'          => 'nullable|string|max:100',
            'numero_etudiant'  => 'nullable|string|max:20',
            'password'         => 'nullable|string|min:8|confirmed',
            'avatar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Mise à jour des infos de base
        $user->name            = $request->name;
        $user->email           = $request->email;
        $user->filiere         = $request->filiere;
        $user->numero_etudiant = $request->numero_etudiant;

        // Nouveau mot de passe (optionnel)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload photo de profil
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Stocker la nouvelle photo
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()
            ->route('profile.index')
            ->with('success', '✅ Profil mis à jour avec succès !');
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER LE COMPTE
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = auth()->user();

        // Supprimer la photo de profil
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('success', 'Compte supprimé avec succès.');
    }
}