@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between mb-4 align-items-center">
    <h5>Edit Party</h5>
    <a href="{{ route('parties.index') }}" class="btn btn-sm btn-secondary">Back</a>
</header>

<form action="{{ route('parties.update', ['party' => $party]) }}" method="POST">
    @method('PUT')
    @include('parties.form', ['mode' => 'edit'])
</form>
@endsection

