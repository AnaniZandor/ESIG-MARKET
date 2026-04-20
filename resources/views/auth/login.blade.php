@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="auth-form-side" style="min-height:calc(100vh - 68px);">
    <div class="auth-form-inner">

        <h2>Bon retour 👋</h2>
        <p class="auth-subtitle">Connecte-toi à ton espace VintageESIG</p>

        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Adresse email</label>
                <input type="email"
                       name="email"
                       class="form-control @error('email') is-error @enderror"
                       placeholder="exemple@esig.tg"
                       value="{{ old('email') }}"
                       required autofocus>
                @error('email')
                    <span class="form-error">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="form-group">
                <label class="form-label">Mot de passe</label>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-error @enderror"
                       placeholder="Ton mot de passe"
                       required>
                @error('password')
                    <span class="form-error">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Se souvenir -->
            <div class="form-group" style="flex-direction:row; display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="remember" id="remember"
                       style="width:16px; height:16px; accent-color:var(--primary);">
                <label for="remember" style="font-size:14px; color:var(--text-mid); margin:0; text-transform:none; letter-spacing:0;">
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="margin-top:8px;">
                <i class="fas fa-arrow-right-to-bracket"></i>
                Se connecter
            </button>

        </form>

        <p class="auth-footer-text">
            Pas encore de compte ?
            <a href="{{ route('register') }}">Créer un compte</a>
        </p>

    </div>
</div>
@endsection