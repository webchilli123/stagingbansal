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
      <th>Transfer Date</th>
        <th>Item Name</th>
        <!-- <th>Issued Quantity (Kg)</th> -->
        <th>Type</th>
        <th>Quantity</th>
        <th>Waste</th>
        <th>Balance</th>
        <!-- <th>Material Id</th> -->
        <!-- <th>Worker Id</th> -->
        <!-- <th>Waste (%)</th>
        <th>Balance (Kg)</th> -->
        
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
        {{-- @if ($transaction->quantity > 0)
          continue;
        @endif --}}
        <?php
            $showOP = false;
            $oldid = '';
            $mainbalance = 0;
            $showBalance = 0;
            if (!empty($transaction->party->id) && $transaction->party->id != $oldid) {
                $showOP = true;
                $oldid = $transaction->party->id;
            }
            $waste = abs((float)$transaction->waste * (float)$transaction->quantity / 100);
            if ( $showOP == true && $mainbalance == 0) {
                $mainbalance = (float)$transaction->openingQty ?? 0;
                $showBalance = $mainbalance + (float)$transaction->quantity - (float)$waste;
            }elseif($showOP == false){
                $mainbalance = (float)$showBalance;
                if ($transaction->type == 'receive' || $transaction->type == 'purchase') {
                    $showBalance = (float)$mainbalance + (float)$tr->quantity - (float)$waste;
                } elseif ($transaction->type == 'sale' || $transaction->type == 'transfer' || $transaction->type == 'used') {
                    $showBalance = (float)$mainbalance + (float)$transaction->quantity - (float)$waste;
                }
            }
            ?>
      <tr>
      <td width="10%">{{ date('d M, Y', strtotime($transaction->created_at))}}</td>
        <td>{{ $transaction->item->name }} </td>
        <td>{{ $transaction->type}}</td>
        <td>{{ $transaction->quantity}}</td>
<td>{{abs((int)$transaction->waste * (int)$transaction->quantity / 100)}}</td>
<td>{{$showBalance}}</td>

        <!-- <td>{{ $transaction->GetpartyMet ? $transaction->GetpartyMet->name : ''}}</td> -->

        <!-- <td>{{ $transaction->Getparty ? $transaction->Getparty->name : ''}}</td> -->

        
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