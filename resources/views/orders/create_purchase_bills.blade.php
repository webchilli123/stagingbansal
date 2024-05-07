@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Purchase Bill</h5>
    <a href="{{ route('order.purchase.bills') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form id="bill-submit" action="{{ route('purchase.bills.store') }}" method="POST">
    @include('orders.bill_form', ['mode' => 'create'])
</form>
@endsection

