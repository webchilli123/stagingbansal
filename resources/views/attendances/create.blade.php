@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Employee Attendance</h5>
    <a href="{{ route('employee-attendances.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('employee-attendances.store') }}" method="POST">
    @include('attendances.form', ['mode' => 'create'])
</form>
@endsection

