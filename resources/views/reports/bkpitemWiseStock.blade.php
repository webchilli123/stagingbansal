@extends('layouts.dashboard')
@section('content')

<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Item Wise Stock</h5>
  </div>
  <div class="col-lg-5 mb-2">
    <form action="{{ route('itemwise-stock.index') }}" method="GET" class="d-flex align-items-center">
      <div class="input-group">
        <span class="input-group-text">Item</span>
        <select name="item_id" class="form-control">
          <option value="" selected>Choose...</option>
          @foreach ($items as $key => $item)
          <option value="{{ $key }}" {{  request('item_id') == $key ? 'selected' : '' }}>{{ $item }}</option>
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
  <table class="table table-striped table-borderless border" id="example">
    <thead>
      <tr>
        <th>Date</th>
        <th>Party Name</th>
        <th>Receive(DR)</th>
        <th>Send(CR)</th>
        <!-- <th>Wastage</th> -->
        <th>Balance</th>
      </tr>
    </thead>
    <tbody>
      @php
          $party_id = request('party_id');
          $balance = 0;
          $prev_party_id = 0;
      @endphp
      @foreach ($transactions as $transaction)

      @if($prev_party_id != $transaction->party_id)
        @php 
           $balance = $transaction->quantity;
        @endphp
        
      @else
        @php
        $total_quantity = 0;
        $wastage = abs($transaction->waste * $transaction->quantity / 100);
        @endphp

        @if($transaction->type == 'transfer' || $transaction->type == 'used')
          @php
          $balance = $balance - abs($transaction->quantity) - $wastage;
          @endphp

          @elseif($transaction->type == 'receive' || $transaction->type == 'purchase')
          @php
          $balance = $balance + abs($transaction->quantity);
          @endphp

        @else
        @endif 

      @endif 

      <tr>

		<td>{{$transaction->transfer ? $transaction->transfer->transfer_date->format('d M, Y') : ''}}
      {{$transaction->orderDate ? $transaction->orderDate->order_date->format('d M, Y') : ''}}</td>
        <td>{{ @$transaction->party->name }}<br>{{@$transaction->transfer->narration}}</td>

        <td>{{$transaction->type == 'receive' ||$transaction->type == 'purchase'? $transaction->quantity : '0'}} 
        </td>
 
        <td>{{$transaction->type == 'transfer' || $transaction->type == 'used' ? abs($transaction->quantity) : '0'}}<br>
          {{$transaction->transfer_transaction ? $transaction->transfer_transaction->name : ''}}<br>
        {{$transaction->transfer_transactionWorker ? $transaction->transfer_transactionWorker->name : ''}}</td>


        <td>{{abs($balance)}}</td>
      </tr>
      @php
      $prev_party_id = $transaction->party_id
      @endphp
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
    $('#example').DataTable({
            // data: name,
            deferRender: true,
            // scrollY:        200,
            scrollCollapse: true,
            scroller: true,
            info: false,
            "bPaginate": false
        });
  });   
</script>
@endpush