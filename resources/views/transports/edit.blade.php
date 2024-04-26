@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Transport</h5>
    <a href="{{ route('transports.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('transports.update', ['transport' => $transport]) }}" method="POST">
    @method('PUT')
    @include('transports.form', ['mode' => 'edit'])
</form>
@endsection

