<!DOCTYPE html>
<html lang="fr">

<head>
    @include('partials.meta')
    @yield('title')
    <title>RH | AUXFIN</title>
    @yield('style')
    @include('partials.style')
    <style>
        .inset-0 {
            z-index: 999999999 !important;
        }
    </style>

<body class="nav-fixed">
    @include('partials.header')
    <div id="layoutSidenav_content">

        @yield('content')

        @include('partials.footer')
    </div>
    </div>
    @include('partials.script')
</body>

</html>
