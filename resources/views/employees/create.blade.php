@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Employee</h5>
    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('employees.store') }}" method="POST">
    @include('employees.form', ['mode' => 'create'])
</form>
@endsection

