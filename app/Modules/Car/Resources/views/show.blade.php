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
        <input type="text" class="form-control" id="model" value="{{ $car->model }}">
    </div>
    <div class="mb-3">
        <label for="year" class="form-label">Year</label>
        <input type="text" class="form-control" id="year" value="{{ $car->year }}">
    </div>
    <div class="mb-3">
        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-control" id="color" value="{{ $car->color }}">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="text" class="form-control" id="price" value="{{ $car->price }}">
    </div>

    <div class="mb-3">
        <label for="file" class="form-label">Car photo</label>
        <input class="form-control" type="file" id="file">
    </div>

    <div class="d-flex justify-content-center">
        <a class="btn btn-secondary" href="{{url('/')}}">Back</a>
    </div>
@endsection
