<!DOCTYPE html>
<html lang="ru">
<head>
    @include('components.head')
    @yield('css')
</head>
<body>
@auth
    @include('components.navigations')
@endauth
<div class="page px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    @yield('content')
</div>
<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>
@yield('js')
</body>
</html>
