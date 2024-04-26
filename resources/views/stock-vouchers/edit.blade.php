@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Stock Voucher</h5>
    <a href="{{ route('stock-vouchers.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('stock-vouchers.update', ['transaction' => $transaction]) }}" method="POST">
    @method('PUT')
    @include('stock-vouchers.form', ['mode' => 'edit'])
</form>

@endsection

