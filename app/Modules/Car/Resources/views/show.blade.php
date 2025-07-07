@extends('layouts.app')

@section('title', 'Editing Car')

@section('content')
    <h1 class="text-center">Editing a car</h1>

    <form id="edit-form-{{ $car->id }}" action="{{ route('cars.update', $car->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" class="form-control" id="brand" name="brand" value="{{ $car->brand }}">
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control" id="model" name="model" value="{{ ucfirst($car->model) }}">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="text" class="form-control" id="year" name="year" value="{{ $car->year }}">
        </div>
        <div class="mb-3">
            <label for="color" class="form-label">Color</label>
            <input type="text" class="form-control" id="color" name="color" value="{{ ucfirst($car->color) }}">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price" value="{{ number_format($car->price, 2) }}">
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Car photo</label>
            <input class="form-control" type="file" id="photo" name="photo">
        </div>
    </form>

    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col">
                <a class="btn btn-secondary" href="{{url('/')}}">Back</a>
            </div>
            <div class="col">
                <a class="btn btn-success" onclick="confirmEdit({{ $car->id }})">Confirm</a>
            </div>
        </div>
    </div>

    <script>
        function confirmEdit(id) {
            /** @type {HTMLFormElement} */
            const form = document.getElementById(`edit-form-${id}`);
            form.submit();
        }
    </script>

@endsection
