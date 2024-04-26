@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>New Party</h5>
    <a href="{{ route('parties.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('parties.store') }}" method="POST">
    @include('parties.form', ['mode' => 'create'])
</form>
@endsection

