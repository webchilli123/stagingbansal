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
  <table class="table table-striped table-borderless border" id="example">
    <thead>
      <tr>
      <!--   <th>Date</th> -->
        <th>#</th>
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
      $oldid = ''; 
      $i = 0; 
      @endphp
        @foreach ($transactions as $k=>$transaction) 
        <?php
          $showBalance = 0;
          $mainbalance = 0; 
          ?> 
          @foreach ($transaction as $key=>$tr)
          <?php  
            $showOP = false;
            if ($tr->item_id != $oldid) {
              $showOP = true;
            }
            $oldid =!empty($tr->item_id) ? $tr->item_id :''; 
            $waste = !empty($tr->waste) ? abs($tr->waste * $tr->quantity / 100) :0; 
            if( $showOP == true && $mainbalance == 0){
              $mainbalance = $tr->openingQty ?? 0;
              $showBalance = $mainbalance + (float)$tr->quantity;  
            }elseif($showOP == false){    
              if($tr->type == 'receive' || $tr->type == 'purchase'){ 
                $showBalance = $showBalance + (float)$tr->quantity - (float)$waste; 
              }elseif($tr->type == 'sale' || $tr->type == 'transfer' || $tr->type == 'used'){ 
                $showBalance = $showBalance + (float)$tr->quantity - (float)$waste;  
              }  
            }  
          ?>
          <tr> 
           <!--  <td>{{$tr->created_at ? $tr->created_at->format('d M, Y') : ''}}</td> -->
            <td>{{$i+1}}</td> 
            <td>{{ $tr->item->name ?? '' }}<br>{{$tr->transfer->narration ?? ''}}</td> 
            <td>{{ !empty($tr->openingQty) && $showOP == true ? $tr->openingQty : '-'}}</td> 
            <td>{{$tr->type == 'receive' || $tr->type == 'purchase' ? abs($tr->quantity) : '0'}}</td>
            <td>{{$tr->type == 'sale' || $tr->type == 'transfer' || $tr->type == 'used' ? abs($tr->quantity) : '0'}}<br>{{$tr->material->name ?? ''}}</td> 
            <td>{{abs($tr->waste * $tr->quantity / 100)}}</td>
            <td>{{($showBalance)}}</td>   
          </tr> 
          <?php $i++; ?>
          @endforeach
        @endforeach
    </tbody>
  </table> 
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