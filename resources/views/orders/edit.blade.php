@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Order</h5>
    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('orders.update', ['order' => $order]) }}" method="POST">
    @method('PUT')
    @include('orders.form', ['mode' => 'edit'])
</form>
@endsection