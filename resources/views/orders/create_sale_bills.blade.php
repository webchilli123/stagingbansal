@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Sale Bill</h5>
    <a href="{{ route('order.sale.bills') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form id="bill-submit" action="{{ route('bills.store') }}" method="POST">
    @include('orders.bill_form_sale', ['mode' => 'create'])
</form>
@endsection

