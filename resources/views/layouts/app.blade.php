<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>VintageESIG — @yield('title', 'Marketplace')</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
{{-- AOS — Animations au scroll --}}
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
{{-- resources/views/layouts/app.blade.php --}}



    {{-- 🔥 VITE (IMPORTANT SI BREEZE) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<body>

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- FLASH MESSAGES --}}
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- MAIN CONTENT --}}
    <main class="main-content container">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    @stack('scripts')

    <script>
function handleSearch(input, formId) {
    // Si le champ est vide → soumettre automatiquement
    // pour afficher tous les articles
    if (input.value === '') {
        // Petit délai pour éviter de soumettre à chaque lettre effacée
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(() => {
            document.getElementById(formId).submit();
        }, 300);
    }
}
</script>

@yield('scripts')
{{-- AOS Init --}}
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 600,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60
    });
</script>

<script>
// ── Navbar scroll effect ──────────────────────────────
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    }
});

// ── Toast notifications ───────────────────────────────
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'circle-check' : 'circle-exclamation'}"
           style="color:var(--${type === 'success' ? 'success' : 'danger'});font-size:18px;"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// ── Afficher les flash messages comme toast ───────────
@if(session('success'))
    document.addEventListener('DOMContentLoaded', () => {
        showToast("{{ session('success') }}", 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', () => {
        showToast("{{ session('error') }}", 'danger');
    });
@endif

// ── Bouton favori avec animation ─────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-favorite').forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });
});

// ── Recherche auto-submit quand champ vide ────────────
function handleSearch(input, formId) {
    if (input.value === '') {
        clearTimeout(window.searchTimer);
        window.searchTimer = setTimeout(() => {
            document.getElementById(formId).submit();
        }, 300);
    }
}
</script>
</body>
</html>