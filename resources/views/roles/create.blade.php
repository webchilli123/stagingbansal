@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Role</h5>
    <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('roles.store') }}" method="POST">
    @include('roles.form', ['mode' => 'create'])
</form>

@endsection