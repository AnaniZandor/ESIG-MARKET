@extends('layouts.app')

@section('title', 'Déconnexion')

@section('content')
<div class="auth-form-side" style="min-height:calc(100vh - 68px);">
    <div class="auth-form-inner" style="text-align:center;">

        <div style="font-size:56px; margin-bottom:20px;">👋</div>

        <h2>Tu pars déjà ?</h2>
        <p class="auth-subtitle">Es-tu sûr de vouloir te déconnecter de VintageESIG ?</p>

        <div style="display:flex; flex-direction:column; gap:12px; margin-top:32px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-full btn-lg">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    Oui, me déconnecter
                </button>
            </form>

            <a href="{{ route('articles.index') }}" class="btn btn-outline-dark btn-full btn-lg">
                <i class="fas fa-arrow-left"></i>
                Non, rester connecté
            </a>
        </div>

    </div>
</div>
@endsection