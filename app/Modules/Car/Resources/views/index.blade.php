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
                    <td>{{ number_format($car->price, 2)}}</td>
                    <td>
                        <a class="btn btn-warning" href="{{url("/$car->id")}}">Edit</a>
                        <button class="btn btn-danger" onclick="confirmDelete({{ $car->id }})">
                            Remove
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{$cars->links()}}
    </div>

    <form id="delete-form-{{ $car->id }}" action="{{ route('cars.destroy', $car->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    /** @type {HTMLFormElement} */
                    const form = document.getElementById(`delete-form-${id}`);
                    form.submit();
                }
            });
        }
    </script>

    @if (request('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: '{{ request('type', 'info') }}', // pode ser 'success', 'error', 'warning', etc.
                    title: '{{ ucfirst(request('type', 'Info')) }}!',
                    text: @json(ucfirst(request('msg'))),
                    timer: 3000,
                    showConfirmButton: false
                });
            })
        </script>
    @endif
@endsection
