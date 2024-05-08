@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Item</h5>
    <a href="{{ route('items.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('items.update', ['item' => $item ]) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}" required>
        </div>
        <div class="col-md-6">
            <label for="quantity" class="form-label">Opening Stock</label>
            <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $item->quantity }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $item->company_name }}" required>
        </div>
        <div class="col-md-6">
            <label for="master_id" class="form-label">Master</label>
            <input type="text" class="form-control" id="master_id" name="master_id" value="{{ $item->master_id }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="part_number" class="form-label">Part Number</label>
            <input type="text" class="form-control" id="part_number" name="part_number" value="{{ $item->part_number }}" required>
        </div>
        <div class="col-md-6">
            <label for="group" class="form-label">Group</label>
            <input type="text" class="form-control" id="group" name="group" value="{{ $item->group }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="item_alias" class="form-label">Item Alias</label>
            <input type="text" class="form-control" id="item_alias" name="item_alias" value="{{ $item->item_alias }}" required>
        </div>
        <div class="col-md-6">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="{{ $item->category }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="hsn_code" class="form-label">HSN Code</label>
            <input type="text" class="form-control" id="hsn_code" name="hsn_code" value="{{ $item->hsn_code }}" required>
        </div>
        <div class="col-md-6">
            <label for="tax_rate" class="form-label">Tax Rate</label>
            <input type="text" class="form-control" id="tax_rate" name="tax_rate" value="{{ $item->tax_rate }}" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mb-5">Edit</button>
</form>

@endsection