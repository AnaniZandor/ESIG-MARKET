{{-- CSS intégré pour le menu hamburger --}}
<style>
/* ============================================================
   MENU HAMBURGER RESPONSIVE
   ============================================================ */

/* Bouton hamburger */
.hamburger-btn {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 28px;
    height: 20px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
    z-index: 1001;
}

.hamburger-btn span {
    width: 100%;
    height: 2.5px;
    background: var(--text-dark);
    border-radius: 3px;
    transition: all 0.3s ease;
}

/* Animation hamburger → croix */
.hamburger-btn.active span:nth-child(1) {
    transform: translateY(8.75px) rotate(45deg);
}

.hamburger-btn.active span:nth-child(2) {
    opacity: 0;
}

.hamburger-btn.active span:nth-child(3) {
    transform: translateY(-8.75px) rotate(-45deg);
}

/* Recherche mobile (cachée par défaut sur desktop) */
.mobile-search {
    display: none;
    width: 100%;
    margin-bottom: 16px;
}

.mobile-search input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    background: var(--bg);
    font-size: 14px;
    outline: none;
    transition: var(--transition);
}

.mobile-search input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-light);
}

/* Menu mobile */
@media (max-width: 768px) {
    .hamburger-btn {
        display: flex;
        margin-left: auto;
    }

    .navbar-search {
        display: none !important;
    }

    .mobile-search {
        display: block;
    }

    .navbar-actions {
        position: fixed;
        top: 68px;
        right: -100%;
        width: 100%;
        max-width: 320px;
        height: calc(100vh - 68px);
        background: white;
        flex-direction: column;
        align-items: flex-start;
        padding: 24px 20px;
        gap: 12px;
        box-shadow: var(--shadow-lg);
        transition: right 0.3s ease;
        overflow-y: auto;
        z-index: 1000;
        border-left: 1px solid var(--border);
    }

    .navbar-actions.active {
        right: 0;
    }

    .navbar-actions .nav-link,
    .navbar-actions .nav-icon-btn,
    .navbar-actions form {
        width: 100%;
    }

    .navbar-actions .nav-link {
        padding: 12px 16px;
        border-radius: var(--radius);
        background: var(--bg);
        font-size: 15px;
    }

    .navbar-actions .nav-icon-btn {
        width: 100%;
        justify-content: flex-start;
        padding: 12px 16px;
        border-radius: var(--radius);
        background: var(--bg);
    }

    .navbar-actions .nav-icon-btn i {
        width: 24px;
    }

    .navbar-actions .btn {
        width: 100%;
        justify-content: center;
        padding: 12px;
    }

    .navbar-actions form {
        display: flex;
    }

    .navbar-actions form .btn {
        width: 100%;
    }

    /* Overlay quand menu ouvert */
    .menu-overlay {
        position: fixed;
        top: 68px;
        left: 0;
        width: 100%;
        height: calc(100vh - 68px);
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        backdrop-filter: blur(2px);
    }

    .menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Ajustements navbar mobile */
    .navbar .container {
        padding: 0 16px;
    }

    .navbar-brand span span:last-child {
        font-size: 16px;
    }
}

/* Très petits écrans */
@media (max-width: 480px) {
    .navbar-brand span span:first-child {
        font-size: 10px;
    }
    
    .navbar-actions {
        max-width: 100%;
    }
}
</style>
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

        {{-- 🔍 BARRE DE RECHERCHE (Desktop uniquement) --}}
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

        {{-- 🍔 BOUTON HAMBURGER (visible uniquement sur mobile) --}}
        <button class="hamburger-btn" id="hamburger-btn" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        {{-- 👤 MENU DROITE --}}
        <div class="navbar-actions" id="navbar-actions">

            {{-- RECHERCHE MOBILE (visible uniquement dans le menu hamburger) --}}
            <form action="{{ route('articles.index') }}" method="GET" class="mobile-search">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher un article..." 
                    value="{{ request('search') }}">
                <button type="submit" style="display:none;"></button>
            </form>

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

{{-- Overlay pour fermer le menu en cliquant à l'extérieur --}}
<div class="menu-overlay" id="menu-overlay"></div>

{{-- JavaScript pour le menu hamburger --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const navbarActions = document.getElementById('navbar-actions');
    const menuOverlay = document.getElementById('menu-overlay');
    const mobileSearchForm = document.querySelector('.mobile-search');
    
    // Fonction pour ouvrir/fermer le menu
    function toggleMenu() {
        hamburgerBtn.classList.toggle('active');
        navbarActions.classList.toggle('active');
        menuOverlay.classList.toggle('active');
        
        // Empêche le scroll du body quand le menu est ouvert
        document.body.style.overflow = navbarActions.classList.contains('active') ? 'hidden' : '';
    }
    
    // Fonction pour fermer le menu
    function closeMenu() {
        hamburgerBtn.classList.remove('active');
        navbarActions.classList.remove('active');
        menuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Événements
    if (hamburgerBtn) {
        hamburgerBtn.addEventListener('click', toggleMenu);
    }
    
    if (menuOverlay) {
        menuOverlay.addEventListener('click', closeMenu);
    }
    
    // Gestion de la recherche mobile (soumission avec Entrée)
    if (mobileSearchForm) {
        mobileSearchForm.addEventListener('submit', function(e) {
            closeMenu();
        });
        
        const mobileSearchInput = mobileSearchForm.querySelector('input');
        if (mobileSearchInput) {
            mobileSearchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    mobileSearchForm.submit();
                }
            });
        }
    }
    
    // Fermer le menu si on clique sur un lien (sauf le bouton submit)
    navbarActions.querySelectorAll('a, button').forEach(item => {
        item.addEventListener('click', function(e) {
            // Ne pas fermer si c'est le bouton de déconnexion (pour éviter la double soumission)
            if (this.closest('form') && this.type === 'submit') {
                return;
            }
            // Ne pas fermer pour l'input de recherche
            if (this.closest('.mobile-search')) {
                return;
            }
            closeMenu();
        });
    });
    
    // Fermer le menu si la fenêtre est redimensionnée au-dessus de 768px
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMenu();
        }
    });
    
    // Échap pour fermer le menu
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && navbarActions.classList.contains('active')) {
            closeMenu();
        }
    });
});
</script>