@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-5 mb-2">
    <h5>Transfer Transactions (History)</h5>
  </div>
  <div class="col-lg-7 mb-2">
    <form action="{{ route('transfer-transactions.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <select name="item_id" class="form-control rounded">
          <option value="" selected>Item</option>
          @foreach ($items as $key => $item)
          <option value="{{ $key }}" {{ request('item_id')==$key ? 'selected' : '' }}>{{ $item }}</option>
          @endforeach
        </select>
        <select name="party_id" class="form-control" required>
          <option value="" selected>Party</option>
          @foreach ($parties as $key => $party)
          <option value="{{ $key }}" {{ request('party_id')==$key ? 'selected' : '' }}>{{ $party }}</option>
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
<section class="table-responsive rounded mb-5">
  <table class="table table-bordered" style="min-width: 60rem;">
    <thead>
      <tr>
        <th>Item Name</th>
        <th>Issued Quantity (Kg)</th>
        <th>Received Quantity (Kg)</th>
        <th>Waste (%)</th>
        <th>Balance (Kg)</th>
        <th>Transfer Date</th>
        {{-- <th>Narration</th> --}}
      </tr>
    </thead>
    <tbody>
      @php
      $balance = 0;
      $quantity = 0;
      $issueqty = 0;
      $leftbal = 0;
      $isstart = true;
      @endphp
      @foreach ($transactions as $transaction)

      @php
       if(!$transaction->transfer->is_receive){
          $issueqty = $transaction->quantity;
        }
      if ($transaction->transfer->is_receive && $transaction->type == 'receive') {
        // $balance = $issueqty-($transaction->quantity - $transaction->waste * $transaction->quantity / 100);
        if ($isstart) {
          $balance = $balance - $transaction->quantity - $transaction->waste * $transaction->quantity / 100;
          // $isstart = false;
        }else{
          $balance = $transaction->balance_quantity - $transaction->quantity - $transaction->waste * $transaction->quantity / 100;
        }
        echo $balance." -----1";
      } 
      else {
        $balance = $transaction->quantity;
        echo $balance." -----2";
      }
      
      
      @endphp

      <tr>

        <td>{{ $transaction->item->name }} -- {{ $transaction->transfer->narration ?? 'NOT GIVEN' }}
          
        </td>

        {{-- issued items --}}
        <td>{{ $transaction->transfer->is_receive ? '' : $transaction->quantity }}</td>

        {{-- received items --}}
        <td>{{ $transaction->transfer->is_receive && $transaction->type == 'receive' ?
          number_format(abs($transaction->quantity), 2, '.', '') : '' }}</td>


        <td>{{ $transaction->transfer->is_receive && $transaction->type == 'receive' ? $transaction->waste : '' }}</td>
        <td>{{ $transaction->transfer->is_receive  ? $balance : '' }}</td>

        <td>{{ $transaction->created_at->format('d M, Y') }}</td>
        {{-- <td> --}}
          {{-- <button type="button" class="btn btn-sm" data-bs-toggle="tooltip"
            title="{{ $transaction->transfer->narration ?? 'NOT GIVEN' }}">
            <i class="fa fa-info-circle text-primary"></i>
          </button> --}}
        {{-- </td> --}}
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

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl) 
    });

  });   
</script>
@endpush