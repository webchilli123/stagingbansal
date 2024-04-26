@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Item Location Stock</h5>
  </div>
  <div class="col-lg-5 mb-2">
    <form action="{{ route('itemLocationStock.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <span class="input-group-text">Item</span>
        <select name="item_id" class="form-control">
          <option value="" selected>Choose...</option>
          @foreach ($items as $key => $item)
          <option value="{{ $key }}" {{ request('item_id') == $key ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
      </div>
      <button class="btn btn-primary ms-1">
        <i class="fa fa-search"></i>
      </button>
          <a href="{{ route('itemLocationStock.index') }}" class="btn btn-success text-white ms-1">
            <i class="fa fa-redo"></i>
          </a>
    </form>
  </div>
</header>

@if(count($transactions) > 0)
<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="parties">
    <thead>
      <tr>
        <!-- <th>Item Name</th> -->
        <th>Party (Location)</th>
        <th>Quantity Available (Kg)</th>
      </tr>
    </thead>
    <tbody>
      @php
          $item_id = request('item_id');
      @endphp
      @foreach ($transactions as $transaction)
      <tr>
        <td><a href="{{ route('item-LocationStock.index') }}?item_id={{ $item_id }}&party_id={{ $transaction->party_id }}" 
            target="_blank">
            {{ $transaction->party->name }}
          </a></td>
        <!-- <td>{{ $transaction->party->name }}</td> -->
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