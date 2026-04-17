<!doctype html>
<html lang="in">

@include('partials.landing.head')

<body class="bg-gray-50 text-gray-800 antialiased">

    @include('partials.landing.navbar')
    @include('partials.landing.hero')
    @include('partials.landing.filter')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @yield('content')
    </main>
    @include('partials.landing.about')
    @include('partials.landing.cta')
    @include('partials.landing.footer')

    @stack('scripts')
</body>

</html>