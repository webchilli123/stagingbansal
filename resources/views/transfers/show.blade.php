@extends('layouts.dashboard')
@section('content')

<header class="d-flex justify-content-between align-items-center mb-4">
    <h5>Transfer - {{ $transfer->transfer_number }}</h5>
    <div>
        <button class="btn btn-sm btn-success text-white me-1" onclick="return window.print();">Print</button>
        <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>    
</header>

<section class="table-responsive mb-4">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead> 
            <tr>
                <th>Transfer No.</th>
                <th>Transfer Date</th>
                <th>Receive</th>
                <th>From Party - To Party</th>
                <th>For Process</th>
                <th>Order No. (Material Source)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transfer->transfer_number }}</td>
                <td>{{ $transfer->transfer_date->format('d M, Y') }}</td>
                <td>{{ $transfer->is_receive ? 'Yes' : 'No' }}</td>
                <td>
                    {{ $transfer->sender->name }} 
                    <i class="fa fa-arrow-right mx-1"></i>
                    {{ $transfer->receiver->name }}
                </td>
                <td>{{ $transfer->process->name ?? "NOT GIVEN"}}</td>
                <td>
                    @if(isset($transfer->order_id))
                    <a href="{{ route('orders.show', ['order' => $transfer->order_id ]) }}" target="_blank">
                        {{ $transfer->order->order_number }}
                    </a>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</section>


<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Narration
   </h6>
<p class="p-3 border mb-4">{{ $transfer->narration }}</p>


<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success me-1"></i> Transfers
</h6>
<section class="table-responsive rounded mb-3">
    <table class="table table-bordered" style="min-width: 60rem;">
        <thead>
            <tr>
                <th></th>
                <th>Item</th>
                <th class="text-center">Quantity (Kg)</th>
                <th>Type</th>
                <th>Party (Location)</th>
                @if ($transfer->is_receive)
                    <th>Waste %</th>
                    <th>Rate</th>
                @endif
                <th>Transfered At</th>
            </tr>
        </thead>
        <tbody> 
            @foreach($transfer->transferTransactions as $transaction)
            <tr>
                <td>
                    @if ($transaction->quantity > 0)
                        <i class="fa fa-arrow-down text-success"></i> IN
                    @else
                    <i class="fa fa-arrow-up text-danger"></i> OUT
                    @endif
                </td>
                <td>{{ $transaction->item->name ?? '' }}</td>
                <td class="text-center">{{  number_format(abs($transaction->quantity), 2, '.', '')  }}</td>
                <td>{{ ucfirst($transaction->type) }}</td>
                <td>{{ $transaction->party->name }}</td>
                @if($transfer->is_receive)
                    <td>{{ isset($transaction->waste) ? $transaction->waste.'%' : '' }}</td>
                    <td>{{ $transaction->rate }}</td>
                @endif
                <td>{{ $transaction->created_at->format('d M, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>


@if($transfer->transactions->count() > 0)
<h6 class="fw-bold mb-3">
    <i class="fa fa-circle text-success"></i>
    Account Vouchers
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
            @foreach($transfer->transactions as $transaction)
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