@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
    <h5> {{ ucfirst($order->type) }} Order - {{ $order->order_number }}</h5>
    <div>
      <button class="btn btn-sm btn-success text-white me-1" onclick="return window.print();">Print</button>
      <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
</header>

<section class="table-responsive mb-4">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead> 
            <tr>
                <th>Order No.</th>
                <th>Type</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Due Date</th>
                <th>Party Name</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ ucfirst($order->type) }}</td>
                <td><span class="badge alert-primary">{{ ucwords($order->status) }}</span></td>
                <td>{{ $order->order_date->format('d M, Y') }}</td>
                <td>{{ $order->due_date->format('d M, Y') }}</td>
                <td>{{ $order->party->name }}</td>
            </tr>
        </tbody>
    </table>
</section>

<h6 class="mb-3 fw-bold">
  <i class="fa fa-circle text-success me-1"></i> Order Items
</h6>
<section class="table-responsive rounded mb-2">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity (Ordered)</th>
                <th>Quantity ({{ $order->type == App\Models\Order::SALE ? 'Sent' : 'Received' }})</th>
                <th>Rate</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $orderItem)
            <tr>
                <td>
                    {{ $orderItem->item->name ?? '' }}
                </td>
                <td>{{ $orderItem->ordered_quantity }}</td>
                <td>{{ $orderItem->received_quantity }}</td>
                <td>{{ $orderItem->rate }}</td>
                <td>{{ $orderItem->total_price }}</td>
            </tr>
            @if ($loop->last)
                <tr class="fw-bold">
                    <td colspan="3"></td>
                    <td>Total Amount</td>
                    <td>{{ number_format($total_amount, '4', '.', '') }}</td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</section>


@if(isset($order->narration))
    <h6 class="fw-bold mb-3">
        <i class="fa fa-circle text-success me-1"></i> Narration
    </h6>
    <p class="p-3 border mb-4">{{ $order->narration ?? '' }}</p>
@endif
@if(isset($order->wa_narration))
    <h6 class="fw-bold mb-3">
        <i class="fa fa-circle text-success me-1"></i> Whatsaap Narration
    </h6>
    <p class="p-3 border mb-4">{{ $order->wa_narration ?? '' }}</p>
@endif

@if($order->transferTransactions->count() > 0)
<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i>
    {{ $order->type == App\Models\Order::SALE ? 'Sales' : 'Purchases' }}
</h6>
<section class="table-responsive rounded mb-2">
    <table class="table table-bordered align-middle" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Transaction  No.</th>
                <th>Item</th>
                <th>Quantity ({{ $order->type == App\Models\Order::SALE ? 'Sent' : 'Received' }})</th>
                <th>Rate</th>
                <th>{{ $order->type == App\Models\Order::SALE ? 'Sale' : 'Purchase' }} Date</th>
                <th>Transport</th>
            </tr>
        </thead>
        <tbody>
            @php
                $isfound = false;
                $count = 1;
                $maxcount=1;
                $prev_transaction_num = 0; 
            @endphp
            @foreach($order->transferTransactions as $transaction)
            @php
                if ($transaction->transaction_id) {
                    $maxcount = $transaction->where('transaction_id',$transaction->transaction_id)->count();
                }else{
                    $maxcount = 1;
                }
                if(isset($transaction->transaction->transaction_number)){
                    if ($prev_transaction_num == $transaction->transaction->transaction_number) {
                        $isfound = true;
                    }else{
                        $prev_transaction_num =$transaction->transaction->transaction_number;
                        $isfound = false;
                    } 
                }
            @endphp
                
            <tr>
                @if (!$isfound)
                    <td rowspan="{{$maxcount}}">
                        @if ($transaction->transaction_id !== null)
                            
                        <form action="{{ route('sendmessage',['order'=>$order]) }}" method="POST">
                            @csrf 
                            <input type="hidden" name="transaction_id" required value="{{$transaction->transaction_id ?? ''}}">
                            <button type="submit" class="btn btn-sm btn-success text-white me-1" >Send whatsApp</button>
                        </form>
                        @endif
                    </td>
                @endif
                {{-- @endif --}}
                <td>{{ $transaction->item->name }}</td>
                <td>{{ number_format(abs($transaction->quantity), 2, '.', '') }}</td>
                <td>{{ $transaction->rate }}</td>
                <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                <td class="p-0" style="max-width:10rem;">
                @if ($order->type == App\Models\Order::SALE)
                   <table class="table mb-0">
                       <tbody>
                           <tr>
                               <th>Name--{{$count}}</th>
                               <td class="border-start">{{ isset($transaction->transport_id) ? @$transaction->transport->name : '' }}</td>
                           </tr>
                           <tr>
                               <th>Bilty No.</th>
                               <td class="border-start">{{ $transaction->bilty_number }}<img src="/images/{{ $transaction->image }}" width="100" height="50px"></td>
                           </tr>
                           <tr>
                               <th>Vehicle No.</th>
                               <td class="border-start">{{ $transaction->vehicle_number }}</td>
                           </tr>
                           <tr style="border-bottom-color: transparent;">
                               <th>Transport Date</th>
                               <td class="border-start">{{ isset($transaction->transport_date) ? date('d M, Y', strtotime($transaction->transport_date)) : '' }}</td>
                           </tr>
                       </tbody>
                   </table>
                @endif
                </td>
                {{-- <td> --}}
                    {{-- <form action="{{ route('sendmessage',['order'=>$order]) }}" method="POST">
                        @csrf 
                        <input type="hidden" name="transcation_id" required value="{{$transaction->transaction_id ?? ''}}">
                        <button type="submit" class="btn btn-sm btn-success text-white me-1" >Send whatsApp</button>
                    </form> --}}
                    
                    
                    {{-- {{$transaction->transaction->transaction_number ?? ''}} --}}
                {{-- </td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

@endif


@if($order->transactions->count() > 0)
<h6 class="fw-bold mb-3"> 
  <i class="fa fa-circle text-success me-1"></i> Account Vouchers
</h6>
<section class="table-responsive rounded mb-5">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th>Voucher Date</th>
                <th>Amount</th>
                <th>Narration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->transactions as $transaction)
            <tr>
                <td>{{ date('d M, Y', strtotime($transaction->transaction_date)) }}</td>
                <td>{{ $transaction->amt_debt }}</td>
                <td>{{ $transaction->narration }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

@endif


@endsection