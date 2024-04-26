@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Location Stock</h5>
  </div>
  <div class="col-lg-5 mb-2">
    <form action="{{ route('location-stock.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <span class="input-group-text">Location</span>
        <select name="party_id" class="form-control">
          <option value="" selected>Choose...</option>
          @foreach ($parties as $key => $party)
          <option value="{{ $key }}" {{  request('party_id') == $key ? 'selected' : '' }}>{{ $party }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary ms-1">
        <i class="fa fa-search"></i>
      </button>
    </form>
  </div>
</header>

@if(isset($transactions))
<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <th>Item Name</th>
        <th>Quantity Available (Kg)</th>
      </tr>
    </thead>
    <tbody>
      @php
          $party_id = request('party_id');
      @endphp
      @foreach ($transactions as $transaction)
      <tr>
        <td>
          <a href="{{ route('transfer-transactions.index') }}?party_id={{ $party_id }}&item_id={{ $transaction->item_id }}" 
            target="_blank">
            {{ $transaction->item->name }}
          </a>
        </td>
        <td>{{ $transaction->total_quantity }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $transactions->links() }}
</section>
@endif

@endsection

@push('scripts')
<script>
  $(document).ready(() => {
    $('select').selectize();
  });   
</script>
@endpush