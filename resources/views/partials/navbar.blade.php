<nav class="navbar">
    <div class="container" style="display:flex; align-items:center; height:68px; gap:20px;">

        {{-- 🔰 LOGO --}}
        <a href="{{ route('articles.index') }}" class="navbar-brand"
           style="display:flex; align-items:center; gap:10px; text-decoration:none;">

            {{-- Logo ESIG --}}
            <img src="{{ asset('images/logo-esig.png') }}"
                 alt="ESIG"
                 style="height:38px; width:auto; object-fit:contain;">

            {{-- Texte --}}
            <span style="display:flex; flex-direction:column; line-height:1.1;">
                <span style="font-size:11px; font-weight:500; color:var(--text-light); letter-spacing:0.08em; text-transform:uppercase;">
                    Marketplace
                </span>
                <span style="font-size:18px; font-weight:700; color:var(--primary); font-family:'Playfair Display',serif;">
                    Vintage<span style="color:var(--secondary);">ESIG</span>
                </span>
            </span>

        </a>

        {{-- 🔍 BARRE DE RECHERCHE --}}
        {{-- oninput : soumet automatiquement quand le champ est vide --}}
        <form action="{{ route('articles.index') }}" method="GET"
              class="navbar-search" id="search-form-nav">
            <input
                type="text"
                name="search"
                id="search-input-nav"
                placeholder="Rechercher un article..."
                value="{{ request('search') }}"
                oninput="handleSearch(this, 'search-form-nav')">
            <button type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        {{-- 👤 MENU DROITE --}}
        <div class="navbar-actions">

            @auth

                {{-- LIENS SELON LE RÔLE --}}
                @if(auth()->user()->role === 'admin')
                    {{-- Admin : panneau admin + marketplace --}}
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="fas fa-shield-halved"></i> Admin
                    </a>
                    <a href="{{ route('articles.index') }}" class="nav-link">
                        <i class="fas fa-store"></i> Marketplace
                    </a>
                @else
                    {{-- User normal --}}
                    <a href="{{ route('articles.index') }}" class="nav-link">
                        <i class="fas fa-store"></i> Accueil
                    </a>
                    <a href="{{ route('favorites.index') }}" class="nav-link">
                        <i class="fas fa-heart"></i> Favoris
                    </a>
                    <a href="{{ route('messages.index') }}" class="nav-link">
                        <i class="fas fa-comment"></i> Messages
                    </a>
                    <a href="{{ route('profile.index') }}" class="nav-link">
                        <i class="fas fa-user"></i> Profil
                    </a>
                @endif

                {{-- 🔔 NOTIFICATIONS --}}
                @php
                    $notifCount = auth()->user()->unreadNotifications->count();
                @endphp
                <a href="{{ route('notifications.index') }}"
                   class="nav-icon-btn"
                   title="Notifications">
                    <i class="fas fa-bell"></i>
                    @if($notifCount > 0)
                        <span class="badge-notif">{{ $notifCount }}</span>
                    @endif
                </a>

                {{-- 🚪 DÉCONNEXION --}}
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-arrow-right-from-bracket"></i>
                        Déconnexion
                    </button>
                </form>

            @else
                {{-- Visiteur non connecté --}}
                <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-right-to-bracket"></i>
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i>
                    Inscription
                </a>
            @endauth

        </div>

    </div>
</nav>