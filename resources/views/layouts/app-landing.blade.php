<!doctype html>
<html lang="in">

@include('partials.landing.head')

<body class="bg-gray-50 text-gray-800 antialiased">

    @include('partials.landing.navbar')

    @if(Route::currentRouteName() == 'landing')
    @include('partials.landing.filter')
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>

    {{-- ==================== CONDITIONAL SECTION ==================== --}}

    @if(Route::currentRouteName() == 'landing')
    @include('partials.landing.about')
    @include('partials.landing.cta')
    @endif

    @include('partials.landing.footer')

    @stack('scripts')
</body>

</html>