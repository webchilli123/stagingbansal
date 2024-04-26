@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-5 mb-2">
    <h5>Item Location Stock (History)</h5>
  </div>
  <div class="col-lg-7 mb-2">
    <form action="{{ route('itemLocationStock.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <select name="item_id" class="form-control rounded">
          <option value="" selected>Item</option>
          @foreach ($items as $key => $item)
          <option value="{{ $key }}" {{ request('item_id')==$key ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
<!--         <select name="party_id" class="form-control" required>
          <option value="" selected>Party</option>
          @foreach ($parties as $key => $party)
          <option value="{{ $key }}" {{ request('party_id')==$key ? 'selected' : '' }}>{{ $party }}</option>
          @endforeach
        </select> -->
      </div>
      <button class="btn btn-primary ms-1">
        <i class="fa fa-search"></i>
      </button>
    </form>
  </div>
</header>

@if(count($transactions) > 0)
<section class="table-responsive rounded mb-5">
  <table class="table table-bordered" style="min-width: 60rem;">
    <thead>
      <tr>
        <!-- <th>Item Name</th> -->
        <th>Transfer Date</th>
        <th>Location(Party)</th>
        <th>Quantity</th>
        <th>Type</th>
        {{-- <th>Narration</th> --}}
      </tr>
    </thead>
    <tbody>
      @php
      $balance = 0;
      $quantity = 0;
      $issueqty = 0;
      $leftbal = 0;
      @endphp
      @foreach ($transactions as $transaction)
       <?php $data [] = $transaction->quantity;   ?>
      <tr>
        <!-- <td>{{ $transaction->item->name }} </td> -->
        <td>{{ date('d M, Y', strtotime($transaction->item->created_at)) }}</td>

        <td>{{ $transaction->party->name}}</td>

        <td>{{ $transaction->quantity}}</td>

        <td>{{ $transaction->type}}</td>
        
        {{-- <td> --}}
          {{-- <button type="button" class="btn btn-sm" data-bs-toggle="tooltip"
            title="{{ $transaction->transfer->narration ?? 'NOT GIVEN' }}">
            <i class="fa fa-info-circle text-primary"></i>
          </button> --}}
        {{-- </td> --}}
      </tr>
      @endforeach
      <tr>
        <th>Total</th>
        <th></th>
        <th>{{array_sum($data)}}</th>
      </tr>
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

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl) 
    });

  });   
</script>
@endpush