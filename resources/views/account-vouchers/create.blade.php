@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Account Voucher</h5>
    <a href="{{ route('account-vouchers.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('account-vouchers.store') }}" method="POST">
    @include('account-vouchers.form', ['mode' => 'create'])
</form>
@endsection

