@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Salary Voucher</h5>
    <a href="{{ route('salary-vouchers.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('salary-vouchers.update', ['attendance' => $attendance]) }}" method="POST">
    @method('PUT')
    @include('attendances.form', ['mode' => 'edit'])
</form>
@endsection

