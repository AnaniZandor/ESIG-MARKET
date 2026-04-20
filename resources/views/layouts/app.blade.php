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

    @yield('scripts')

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
</body>

</body>
</html>