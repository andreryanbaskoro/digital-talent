<!doctype html>
<html lang="en">

@include('partials.admin.head')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        @include('partials.admin.navbar')

        @include('partials.admin.sidebar')

        @yield('content')

        @include('partials.admin.footer')


    </div>

    @include('partials.admin.script')
    @stack('scripts')
</body>

</html>