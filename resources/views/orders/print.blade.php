@extends('layouts.dashboard')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    i.fa.fa-folder.openDetail.greencolo {
        color: #d8c510;
    }

    i.fa.fa-folder.openDetail:hover {
        color: #d8c510;
    }
</style>
<header class="d-flex justify-content-between align-items-center mb-4">
    <h5> {{ ucfirst($order->type) }} Order - {{ $order->order_number }}</h5>
    <div>
        <button class="btn btn-sm btn-success text-white me-1" onclick="return window.print();">Print</button>
        <a href="{{ route('order.bills') }}" class="btn btn-sm btn-secondary">Back</a>
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
    <td>{{ optional($order->order_date)->format('d M, Y') }}</td>
    <td>{{ optional($order->due_date)->format('d M, Y') }}</td>
    <td>{{ $order->party->name ?? '' }}</td>
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
                <th>Transaction No.</th>
                <th>Type</th>
                <th>Item</th>
                <th>Quantity ({{ $order->type == App\Models\Order::SALE ? 'Sent' : 'Received' }})</th>
                <th>Rate</th>
                <th>Total</th>
                <th>{{ $order->type == App\Models\Order::SALE ? 'Sale' : 'Purchase' }} Date</th>
                <th>Transport</th>
            </tr>
        </thead>
        <tbody>
            @php
            $val_total_one = 0;
            $val_total_qty = 0;
            $isfound = false;
            $count = 1;
            $maxcount=1;
            $prev_transaction_num = 0;
            $prev_transaction_num_last = 0;
            $builty = '';
            $sholast = false;
            @endphp
            @foreach($order->transferTransactions as $k=> $transaction)
            @php
            $is_change = false;
            if ($transaction->transaction_id) {
                $maxcount = $transaction->where('transaction_id',$transaction->transaction_id)->count();
            }else{
                $maxcount = 1;
            }


            if( (isset($transaction->transaction->transaction_number) && $prev_transaction_num_last != $transaction->transaction->transaction_number && $k > 0)){
                $is_change = true;
            }else{
                $val_single = (float)$transaction->rate * (float)(abs($transaction->quantity));
                $val_total_one += (float)$val_single;
                $val_total_qty += abs($transaction->quantity);
            }

            //if(count($order->transferTransactions) - 1 == $k){
           //     $is_change = true;
           // }

            if(isset($transaction->transaction->transaction_number)){
                if ($prev_transaction_num == $transaction->transaction->transaction_number) {
                    $isfound = true;
                }else{
                    $prev_transaction_num =$transaction->transaction->transaction_number;
                    $isfound = false;
                }
            }
            @endphp

            @if( $is_change  )
            <tr>
                    <td colspan='3'></td>
                    <td>{{number_format($val_total_qty,2) ?? 0}}</td>
                    <td></td>
                    <td>{{number_format($val_total_one,2) ?? 0}}</td>
                    <td colspan='2'></td>
                </tr>
                <?php $val_total_one = 0;
                $val_total_qty = 0;
                $val_single = (float)$transaction->rate * (float)(abs($transaction->quantity));
                $val_total_one += (float)$val_single;
                $val_total_qty += (float)(abs($transaction->quantity));
                ?>
            @endif

            <tr>
                @if (!$isfound)
                    <td rowspan="{{$maxcount}}">
                        <input type="hidden" name="transaction_id" required value="{{$transaction->transaction_id ?? ''}}">
                        <button data-transaction="{{$transaction->transaction_id ?? ''}}" data-toggle='modal' data-narration="{{$order->wa_narration ?? ''}}" data-target="#narrationModal" class="btn btn-sm btn-success text-white me-1 narrationModal">Send whatsApp</button>
                        <a target='_blank' href="{{url('pdfpreview/'.$transaction->transaction_id) ?? ''}}"  class="btn btn-sm btn-success text-white me-1 ">Preview</a>
                        @if(isset($transaction->transaction_id))
                        <a href="{{route('orders.sales.edit',$transaction->transaction_id)}}" class="btn btn-sm btn-secondary">Edit</a>
                        @endif
                    </td>
                @endif
                {{-- @endif --}}
                <td>{{ !empty($transaction->return) && $transaction->return == 1 ? 'Return' : ucfirst($transaction->type) }}</td>
                <td>{{ $transaction->item->name }}</td>
                <td>{{ number_format(abs($transaction->quantity), 2, '.', '') }}</td>
                <td>{{ $transaction->rate }}</td>
                <td>{{ (float)$transaction->rate * (float)(abs($transaction->quantity)) }}</td>
                <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                <td class="p-0" style="max-width:10rem;text-align:center;">
                    @if ($order->type == App\Models\Order::SALE && $builty != $transaction->bilty_number)
                    <i class='fa fa-folder openDetail' data-toggle='tooltip' data-placement='top'></i>
                    <table class="table mb-0" style='display:none;'>
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
                    @if ($order->type == App\Models\Order::SALE && $builty == $transaction->bilty_number)
                    "
                    @endif
                    <?php $builty = $transaction->bilty_number; ?>
                </td>
                {{-- <td> --}}
                {{-- <form action="{{ route('sendmessage',['order'=>$order]) }}" method="POST">
                @csrf
                <input type="hidden" name="transcation_id" required value="{{$transaction->transaction_id ?? ''}}">
                <button type="submit" class="btn btn-sm btn-success text-white me-1">Send whatsApp</button>
                </form> --}}


                {{-- {{$transaction->transaction->transaction_number ?? ''}} --}}
                {{-- </td> --}}
            </tr>

            @if(   $loop->last )
            <?php
            /* $val_single = (float)$transaction->rate * (float)(abs($transaction->quantity));
            $val_total_one += (float)$val_single;  */
            ?>
                <tr>
                <td colspan='3'></td>
                    <td>{{number_format($val_total_qty,2) ?? 0}}</td>
                    <td></td>
                    <td>{{number_format($val_total_one,2) ?? 0}}</td>
                    <td colspan='2'></td>
                </tr>
            @endif

            @php $prev_transaction_num_last = isset($transaction->transaction->transaction_number) && $transaction->transaction->transaction_number ? $transaction->transaction->transaction_number: '';
             @endphp
            @endforeach
        </tbody>
    </table>
</section>

@endif

<div class="modal" id="narrationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="POST" class='pdf_form'>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Narration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" class="transaction_id" name="transaction_id" >
                    <input type="hidden" class="show_preview" name="show_preview" >
                    <div class="form-group">
                        <textarea class="form-control narration_input" rows="8" placeholder="Enter Narration" name="narration"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                <td>{{number_format($transaction->amt_debt,2) ?? 0}}</td>
                <td>{{ $transaction->narration }}</td>
            </tr>
            @break
            @endforeach
        </tbody>
    </table>
</section>

@endif


@endsection


@push('scripts')
<script>
    $(document).ready(() => {
        $(document).on('click', '.openDetail', function() {
            $(this).parent().find('table').toggle();
            $(this).toggleClass('greencolo');
        });
        $(document).on('click', '.narrationModal', function() {
            var trasaction = $(this).data('transaction');
            var narration = $(this).data('narration');
            $('.transaction_id').val(trasaction);
            $('.narration_input').val(narration);
            $('.show_preview').val(false);
        });
        $(document).on('click', '.previewpdf', function() {
            var trasaction = $(this).data('transaction');
            var narration = $(this).data('narration');
            $('.transaction_id').val(trasaction);
            $('.narration_input').val(narration);
            $('.show_preview').val(true);
            $('.pdf_form').submit();
        });

    });
</script>
@endpush
