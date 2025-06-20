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
            <div class="container">
                @yield('content')
            </div>
        </main>

        @include('footer')
    </body>
</html>
