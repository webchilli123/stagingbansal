@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Salary Voucher</h5>
    <a href="{{ route('salary-vouchers.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('salary-vouchers.store') }}" method="POST">
    @include('salary-vouchers.form', ['mode' => 'create'])
</form>
@endsection

