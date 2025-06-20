<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Minha Aplicação')</title>
        @vite(['resources/js/app.js'])
    </head>
    <body>

        @include('header')

        <main>
            @yield('content')
        </main>

        @include('footer')
    </body>
</html>
