@extends('layouts.app')

@section('title', 'Lista de Carros')

@section('content')
    <h1 class="text-center mt-3 mb-3">Cars Available</h1>


    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($cars as $car)
                <div class="swiper-slide">
                    <div class="p-4 border rounded text-center bg-white shadow">
                        <img src="https://www.w3schools.com/w3css/img_car.jpg" alt="Car" class="img-fluid rounded-4 shadow-sm">
                        <h3 class="mt-3 text-lg font-bold">{{ $car->brand }}</h3>
                        <div>
                            <p>
                                <span class="fw-bold">Model:</span>
                                {{ $car->model }}
                            </p>
                            <p>
                                <span class="fw-bold">Year:</span>
                                {{ $car->year }}
                            </p>
                            <p>
                                <span class="fw-bold">Color:</span>
                                {{ $car->color }}
                            </p>
                            <p>
                                <span class="fw-bold">Price:</span>
                                {{ number_format($car->price, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new Swiper(".mySwiper", {
                loop: true,
                slidesPerView: 3,
                spaceBetween: 20,
                autoplay: false,
                breakpoints: {
                    640: {slidesPerView: 1},
                    768: {slidesPerView: 2},
                    1024: {slidesPerView: 3},
                },
            });
        });
    </script>
@endsection
