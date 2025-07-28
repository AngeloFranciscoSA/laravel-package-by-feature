<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
        />
        <meta charset="UTF-8">
        <title>@yield('title', 'Minha Aplicação')</title>
        @vite(['resources/js/app.js'])
    </head>
    <body>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        @include('header')

        <main>
            <div class="container">
                @yield('content')
            </div>
        </main>

        @include('footer')
    </body>
</html>
