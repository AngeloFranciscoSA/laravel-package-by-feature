@extends('layouts.app')

@section('title', 'Editing Car')

@section('content')
    <h1 class="text-center">Editing a car</h1>

    <div class="mb-3">
        <label for="brand" class="form-label">Brand</label>
        <input type="text" class="form-control" id="brand" value="{{ $car->brand }}">
    </div>
    <div class="mb-3">
        <label for="model" class="form-label">Model</label>
        <input type="text" class="form-control" id="model" value="{{ ucfirst($car->model) }}">
    </div>
    <div class="mb-3">
        <label for="year" class="form-label">Year</label>
        <input type="text" class="form-control" id="year" value="{{ $car->year }}">
    </div>
    <div class="mb-3">
        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-control" id="color" value="{{ ucfirst($car->color) }}">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="text" class="form-control" id="price" value="{{ number_format($car->price, 2) }}">
    </div>

    <div class="mb-3">
        <label for="file" class="form-label">Car photo</label>
        <input class="form-control" type="file" id="file">
    </div>

    <div class="d-flex justify-content-center">
        <div class="row">
            <div class="col">
                <a class="btn btn-success" href="{{url('/')}}">Confirm</a>
            </div>
            <div class="col">
                <a class="btn btn-secondary" href="{{url('/')}}">Back</a>
            </div>
        </div>
    </div>
@endsection
