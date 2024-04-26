@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Employee Attendance</h5>
    <a href="{{ route('employee-attendances.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('employee-attendances.update', ['attendance' => $attendance]) }}" method="POST">
    @method('PUT')
    @include('attendances.form', ['mode' => 'edit'])
</form>
@endsection

