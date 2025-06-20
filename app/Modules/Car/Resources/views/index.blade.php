@extends('layouts.app')

@section('title', 'Lista de Carros')

@section('content')
    <h1 class="text-center">Cars Available</h1>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Brand</th>
                <th scope="col">Model</th>
                <th scope="col">Year</th>
                <th scope="col">Color</th>
                <th scope="col">Price</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cars as $car)
                <tr>
                    <th>{{ $car->id }}</th>
                    <td>{{ $car->brand }}</td>
                    <td>{{ ucfirst($car->model) }}</td>
                    <td>{{ $car->year }}</td>
                    <td>{{ ucfirst($car->color) }}</td>
                    <td>{{ $car->price }}</td>
                    <td><button class="btn btn-primary">Edit</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{$cars->links()}}
    </div>
@endsection
