@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Stock Voucher</h5>
    <a href="{{ route('stock-vouchers.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('stock-vouchers.store') }}" method="POST">
    @include('stock-vouchers.form', ['mode' => 'create'])
</form>
@endsection

