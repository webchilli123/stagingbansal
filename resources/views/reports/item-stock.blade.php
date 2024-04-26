@extends('layouts.dashboard')
@section('content')
<?php use App\Models\TransferTransaction;?>
<header class="row no-gutters mb-4">
  <div class="col-lg-7 mb-2">
    <h5>Item Stock</h5>
  </div>

<!-- <form action="{{ route('item-stock.index') }}" method="GET">

    <div class="input-group">
        <input type="text" class="form-control mr-2" name="name" placeholder="Search" id="name">
        <a href="{{ route('item-stock.index') }}" class=" mt-1">
        <span class="input-group-btn mr-5 mt-1">
            <button class="btn btn-info" type="submit" title="name">
                <span class="fas fa-search"></span>
            </button>
        </span>
            <span class="input-group-btn">
                <button class="btn btn-danger" type="button" title="Refresh page">
                    <span class="fas fa-sync-alt"></span>
                </button>
            </span>
        </a>
    </div>
</form> -->
<!--   <div class="col-lg-5 mb-2 dataTables_filter">
    <form action="{{ route('item-stock.index') }}" method="GET" class="d-flex align-items-center">
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
          <a href="{{ route('item-stock.index') }}" class="btn btn-success text-white ms-1">
            <i class="fa fa-redo"></i>
          </a>
    </form>
  </div> -->
</header>

@if(count($items) > 0)
<section class="table-responsive-md rounded mb-5">
  <table class="table table-striped table-borderless border" id="example">
    <thead>
      <tr>
        <th>Item Name</th>
        <th>Opening Balance</th>
        <th>Received QTY</th>
        <th>Transfered QTY</th>
        <th>Avaiable QTY (Kg)</th>
        <!-- <th>Party (Location)</th> -->
      </tr>
    </thead>
    <tbody>
      @if(count($items) < 0)
      @foreach ($items as $item)
      <?php    
      // $total_quantity = TransferTransaction::where('item_id',$transaction->id)->sum('quantity');
    /*   if(empty($item)){
        continue;
      } */
      $data = TransferTransaction::where('item_id',$item->id)->where('party_id',4029)->select('quantity','type','id')->get();
      $data2 = TransferTransaction::where('item_id',$item->id)->where('party_id',1001)->select('return','quantity','type','id')->get();
       
      $trasasctionQTY = 0; 
      $decreasedQTY = 0;  
      /* if(!empty($data2)){
        foreach($data2 as $detail){  
            if(!empty($detail->type) && $detail->type == 'return'){
              $trasasctionQTY += (float)$detail->quantity ?? 0; 
            }
          }
        }  */ 
      if(!empty($data2)){
        foreach($data2 as $detail){  
            if(!empty($detail->type) && $detail->type == 'purchase' || !empty($detail->type) && $detail->type == 'receive'){
              if($detail->return == 1){ 
                $decreasedQTY -= (float)$detail->quantity ?? 0;  
              } 
            } 
          if(!empty($detail->type) && $detail->type == 'sale' || !empty($detail->type) && $detail->type == 'transfer'){ 
            if($detail->return == 1){ 
             $trasasctionQTY += (float)$detail->quantity ?? 0;  
            }  
          }
          }
      }  
      if(!empty($data)){
        foreach($data as $detail){  
            if(!empty($detail->type) && $detail->type == 'purchase' || !empty($detail->type) && $detail->type == 'receive'){
              $trasasctionQTY += (float)$detail->quantity ?? 0;  
            } 
          if(!empty($detail->type) && $detail->type == 'sale' || !empty($detail->type) && $detail->type == 'transfer'){ 
               $decreasedQTY += -(float)$detail->quantity ?? 0;   
          }
        }
      }  
      ?>
      <tr>
        <td>{{ $item->name }}</td>

        <td>{{$item->quantity}}</td>

        <td>{{ $trasasctionQTY  }}</td>
        <td> {{$decreasedQTY}} </td>
        <td> {{($trasasctionQTY + $item->quantity - $decreasedQTY)}}  </td>
      </tr>
      @endforeach
      @endif
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
            // "bPaginate": false,
            "lengthMenu": [20, 40, 60, 80, 100],
        "pageLength": 20
        });

  });   
</script>
@endpush