@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Staff Type</h5>
    <a href="{{ route('staff-types.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('staff-types.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" required>
    </div>

    <button type="submit" class="btn btn-primary mb-5">Save</button>
</form>

@endsection