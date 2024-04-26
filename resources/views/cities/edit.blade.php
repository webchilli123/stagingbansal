@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit City</h5>
    <a href="{{ route('cities.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('cities.update', ['city' => $city ]) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $city->name }}" required>
    </div>

    <button type="submit" class="btn btn-primary mb-5">Edit</button>
</form>

@endsection