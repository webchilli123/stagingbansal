@extends('layouts.dashboard')
@section('content')


<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Party Wise Stock</h5>
  </div>
  <div class="col-lg-5 mb-2">
    <form action="{{ route('party-stock.index') }}" method="GET" class="d-flex align-items-center">
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
        <th>Date</th>
        <th>Item Name</th>
        <th>Op Stock</th>
        <th>Receive(DR)</th>
        <th>Send(CR)</th>
        <th>Wastage</th>
        <th>Balance</th>
      </tr>
    </thead>
    <tbody>
      @php
          $party_id = request('party_id');
          $balance = 0;
          $prev_item_id = 0;
      @endphp
      @foreach ($transactions as $transaction)

      @if($prev_item_id != $transaction->item_id)
        @php 
           $balance = $transaction->quantity;
        @endphp

        @if($transaction->GetopStock)
          @php
          $balance = $balance + abs($transaction->GetopStock->quantity);
          @endphp
          @endif 
        
      @else
        @php
        $total_quantity = 0;
        $wastage = abs($transaction->waste * $transaction->quantity / 100);
        @endphp

        @if($transaction->type == 'transfer' || $transaction->type == 'used')
          @php
          $balance = $balance - abs($transaction->quantity) - $wastage;
          @endphp

          @elseif($transaction->type == 'receive')
          @php
          $balance = $balance + abs($transaction->quantity);
          @endphp
        @else
        @endif
        @if($transaction->GetopStock)
          @php
          $balance = $balance + abs($transaction->GetopStock->quantity);
          @endphp
          @endif 

      @endif 


      <tr>





		<td>{{$transaction->transfer ? $transaction->transfer->transfer_date->format('d M, Y') : ''}}</td>
        <td>{{ @$transaction->item->name }}<br>{{@$transaction->transfer->narration}}</td>
        
        <td>{{ @$transaction->GetopStock ? $transaction->GetopStock->quantity : ''}}</td>

        <td>{{$transaction->type == 'receive' ? abs($transaction->quantity) : '0'}}</td>
        @if($transaction->type == 'transfer')
        <td>{{$transaction->type == 'transfer'  ? abs($transaction->quantity) : '0'}}</td>

        @elseif($transaction->type == 'used')
        <td>{{$transaction->type == 'used'  ? abs($transaction->quantity) : '0'}}<br>
          {{$transaction->transfer_transaction ? $transaction->transfer_transaction->name : ''}}</td>
        @else
        <td>-</td>
        @endif 

        <td>{{abs($transaction->waste * $transaction->quantity / 100)}}</td>
        <td>{{abs($balance)}}</td>
      </tr>
      @php
      $prev_item_id = $transaction->item_id
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
  });   
</script>
@endpush